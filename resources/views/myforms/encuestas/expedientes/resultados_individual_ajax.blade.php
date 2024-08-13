@foreach ($encuestas as $key => $encuesta)
    @include('myforms.encuestas.expedientes.resultados_individual', [
        'encuesta' => $encuesta,
    ])
@endforeach
{{ $encuestas->appends(request()->query())->links() }}