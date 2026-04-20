@php
    $user = $conciliacion->getUser(196);
  $apoderado = $conciliacion->personasPorTipo('apoderado')->first();

@endphp
<div class="card card-outline card-info p-2" id="apoderado_solicitante">
    <div class="card-header">
        <h4 align="center">
            <strong>APODERADO DE LA PARTE SOLICITANTE</strong>
        </h4>
    </div>
    <div class="card-body" id="form_apoderado">
        <div id="user_apoderado_form" style="width: 100%">
            {{--  @include('myforms.conciliaciones.componentes.user_apoderado_form') --}}
            @if ($apoderado)
                <input type="hidden" name="persona_externa_id" value="{{ $apoderado->id }}">
                {{--  @include('myforms.conciliaciones.componentes.formulario_apoderado', [
            'disabled' => !Request::has('id') || $user->idnumber != null ? 'disabled' : '',
        ])  --}}
                @include('myforms.categorias.refs_aditional_data', [
                    'data' => $apoderado->persona->preguntas()->orderBy('orden', 'asc')->get(),
                    'col' => 3,
                    'model' => $apoderado,
                    'disabled' => 'disabled' ,
                    // 'design' => 'card_question',
                ])
            @endif
        </div>
    </div>
</div>
