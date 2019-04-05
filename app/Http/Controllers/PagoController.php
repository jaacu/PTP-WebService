<?php

namespace App\Http\Controllers;

use App\Pago;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Jobs\UpdateJob;

class PagoController extends Controller
{
    protected $auth;
    
    protected $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://test.placetopay.com/redirection/']);

        $seed = date('c');
        
        $nonce = bin2hex(random_bytes(16));

        $nonceBase64 = base64_encode($nonce);

        $login = env('PTP_IDENTIFICADOR');

        $secretKey = env('PTP_SECRET_KEY');


        $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));

        $this->auth = [
            'login' => $login,
            'seed' => $seed,
            'nonce' => $nonceBase64,
            'tranKey' => $tranKey
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagos = Pago::latest()->paginate(10);
        return view('pagos.index', compact('pagos'));
    }

    /**
     * Make a HTTP request with Guzzle
     *
     * @param string $url
     * @param array|null $data  //extra data, the auth is already included
     * @return \Illuminate\Http\Response|\App\Pago
     */
    public function makeHttpRequest($url, $extraData = null, $reference = null)
    {
        try {
            $data = array_merge(
                ['auth' => $this->auth],
                $extraData ?? []
        );
            $request = $this->client->post($url, [
            'json' => $data
        ]);

            $response = $request->getBody()->getContents();
            $response = json_decode($response);


            if (is_null($extraData) && is_null($reference)) {
                return $response;
            } else {
                if ($response->status->status != 'OK') {
                    throw new \GuzzleHttp\Exception\ClientException();
                }

                $pago = Pago::create([
                'reference' => $reference,
                'requestId' => $response->requestId,
            ]);
            
                UpdateJob::dispatch($pago, $this->auth)
                ->delay(now()->addMinutes(1));

                return redirect()->away($response->processUrl);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return $this->redirectWithError();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:1',
            'description' => 'required|string|min:1'
        ]);

        $expiration = now()->addMinutes(10)->toIso8601String();
        $reference = str_random();
        $data = [
            'payment' => [
                'reference' => $reference,
                'description' => $validated['description'],
                'amount' => [
                    'currency' => 'COP',
                    'total' => $validated['price']
                ],
            ],
            'expiration' => $expiration,
            'returnUrl' => route('pagos.show', $reference),
            'ipAddress' => $request->ip(),
            'userAgent' => $request->userAgent()
        ];

        $url = 'api/session';

        return $this->makeHttpRequest($url, $data, $reference);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $pago
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago, Request $request)
    {
        if (!$pago->isChecked()) {
            $pago = $this->update($pago);
        }
        return view('pagos.show', compact('pago'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Pago  $pago
     * @return \Illuminate\Http\Response
     */
    public function update(Pago $pago)
    {
        $url = "api/session/{$pago->requestId}";

        $response = $this->makeHttpRequest($url);
        
        $pago->update([
            'status' => optional($response->payment[0])->status ?? $response->status,
            'payer' => optional($response->request)->payer ?? null,
            'payment' => ($response->request->payment),
            'check' => !is_null(optional($response->request)->payer)
        ]);

        return $pago;
    }

    /**
     * Redirect back with errors
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectWithError($error = 'Algo ha salido mal! Por favor intentalo de nuevo!')
    {
        return redirect()->back()->withErrors($error);
    }
}
