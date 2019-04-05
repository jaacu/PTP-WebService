<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Pago;
use GuzzleHttp\Client;

class UpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pago;

    protected $auth;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pago $pago, $auth)
    {
        $this->pago = $pago;
        $this->auth = $auth;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->pago->isChecked()) {
            return;
        }

        $url = "api/session/{$this->pago->requestId}";
        $client = new Client(['base_uri' => 'https://test.placetopay.com/redirection/']);
        $response = $client->post($url, [
            'json' => $this->auth
        ]);

        $this->pago->update([
            'status' => optional($response->payment[0])->status ?? $response->status,
            'payer' => optional($response->request)->payer ?? null,
            'payment' => ($response->request->payment),
            'check' => true
        ]);
    }
}
