@extends('master')

@section('content')
<div class="row card">
    <div class="card-body col-md-12 p-3">
            <div class="row">

                <div class="col-md-12 text-center">
                    <h4> Datos de la Transacion </h4>
                </div>

                <div class="col-md-6 text-center">
                    <h6><label for="requestId"> RequestId</label></h6>
                    <input type="text" id="requestId" value="{{ $pago->requestId }}" readonly class="form-control text-center"> 
                </div>
                <div class="col-md-6 text-center">
                    <h6><label for="reference"> Referencia </label></h6>
                    <input type="text" id="reference" value="{{ $pago->reference }}" readonly class="form-control text-center"> 
                </div>
                <div class="col-md-4 mt-3 mx-auto text-center">
                    <h6><label for="status"> Estatus </label></h6>
                    <input type="text" id="status" value="{{ $pago->status->status }}" readonly class="form-control text-center text-{{ $pago->getStatusColor()}}"> 
                </div>
                <div class="col-md-8 mt-3 mx-auto text-center">
                    <h6><label for="status_message"> Mensaje del estatus </label></h6>
                    <input type="text" id="status_message" value="{{ $pago->status->message }}" readonly class="form-control text-center"> 
                </div>
                @if ( !$pago->isPending() && !is_null($pago->payer) )
                <div class="col-md-12 my-3 text-center">
                    <h4>Datos del Comprador</h4>
                </div>

                <div class="col-md-4 text-center">
                    <h6><label for="nombre"> Nombre </label></h6>
                    <input type="text" id="nombre" value="{{ $pago->payer->name }}" readonly class="form-control text-center"> 
                </div>
                <div class="col-md-4 text-center">
                    <h6><label for="apellido"> Apellido </label></h6>
                    <input type="text" id="apellido" value="{{ $pago->payer->surname }}" readonly class="form-control text-center"> 
                </div>
                <div class="col-md-2 text-center">
                    <h6><label for="tipo_documento"> Tipo de documento </label></h6>
                    <input type="text" id="tipo_documento" value="{{ $pago->payer->documentType }}" readonly class="form-control text-center"> 
                </div>
                <div class="col-md-2 text-center mx-auto">
                    <h6><label for="document"> Documento </label></h6>
                    <input type="text" id="document" value="{{ $pago->payer->document }}" readonly class="form-control text-center"> 
                </div>

                @endif

                <div class="col-md-12 my-3 text-center">
                    <h4>Datos del Pago</h4>
                </div>

                <div class="col-md-4 text-center">
                    <h6><label for="monto"> Monto </label></h6>
                    <input type="text" id="monto" value="{{ $pago->payment->amount->total }} ({{ $pago->payment->amount->currency }})" readonly class="form-control text-center"> 
                </div>

                <div class="col-md-4 text-center">
                    <h6><label for="descripcion"> Descripcion </label></h6>
                    <textarea id="descripcion" readonly class="form-control text-center"> {{ $pago->payment->description }}</textarea>
                </div>
                <div class="col-md-4 text-center">
                    <h6><label for="creacion"> Creada </label></h6>
                    <input type="text" id="creacion" value="{{ $pago->created_at }}" readonly class="form-control text-center"> 
                </div>

                <div class="col-md-4 text-center mx-auto my-4">
                    <a href="{{ route('home') }}" class="btn btn-primary">Volver al inicio.</a>
                </div>
            </div>
    </div>


</div>
@endsection