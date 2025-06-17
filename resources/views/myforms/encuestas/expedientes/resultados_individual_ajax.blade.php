@forelse ($encuestas as $key => $encuesta)
    @include('myforms.encuestas.expedientes.resultados_individual', [
        'encuesta' => $encuesta,
    ])
@empty
    <div class="alert alert-info mt-3">
        No hay resultados para mostrar.
    </div>
@endforelse

{{ $encuestas->appends(request()->query())->links() }} 