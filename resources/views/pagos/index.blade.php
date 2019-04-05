@extends('master')

@section('content')
<div class="row card">
    <div class="card-body col-md-12 p-3">
        <form action="{{ route('pagos.store')}}" method="POST">
            @csrf
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <label for="price">Precio (en COP)</label>
                        <input type="number" class="form-control" id="price" name="price" min="1" required>
                    </div>
                    <div class="col-md-6 text-center">
                        <label for="description">Descripcion del pago</label>
                        <textarea class="form-control" id="description" name="description" required> </textarea>
                    </div>
                    <div class="col-md-6 text-center mx-auto my-4">
                        <button type="submit" class="btn btn-secondary btn-lg">Enviar</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<div class="row card p-3 my-3">
        {{ $pagos->links() }}
    @foreach ($pagos as $pago)
    <div class="card-body col-md-12">
        <h4>Referencia: <strong>{{ $pago->reference }}</strong> 
        <span class="badge badge-pill badge-{{ $pago->getStatusColor() }} ">{{ optional($pago->status)->status ?? 'PENDING' }}</span>
        <a href="{{ route('pagos.show', $pago) }}" class="btn btn-primary">Ver detalles.</a> </h4>
    </div> 
    @endforeach
    {{ $pagos->links() }}
</div>
@endsection