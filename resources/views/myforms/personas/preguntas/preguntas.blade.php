@foreach ($data as $key => $reference)
    {{-- @include('myforms.categorias.partials.ajax.pregunta') --}}
    @include('myforms.personas.preguntas.pregunta')
@endforeach 