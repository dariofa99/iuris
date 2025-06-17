
<form id="myEvaNivSatForm{{ $encuesta->id }}">
    <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">
    <div class="row" id="renderQuestion">
        @include('myforms.categorias.refs_aditional_data', [
            'data' => $encuesta->encuesta->preguntas,
            'col' => 12,
            'model' => $encuesta,
            'disabled' => 'disabled',
            'design' => 'card_question',
        ])
    </div>
</form> 

