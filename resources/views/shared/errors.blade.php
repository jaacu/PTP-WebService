<div class="alert alert-danger notification">
    <h1 class="alert-heading">
        Se encontraron algunos errores!
    </h1>
    @foreach ($errors->all() as $error)
        {{$error}}
    @endforeach
</div>