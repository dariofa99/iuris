@foreach ($encuestas as $key => $encuesta)
    @include('myforms.encuestas.conciliaciones.resultados_individual', [
        'encuesta' => $encuesta,
    ])
@endforeach
{{ $encuestas->appends(request()->query())->links() }}