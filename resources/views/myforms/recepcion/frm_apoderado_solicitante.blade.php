@php
    $user = $conciliacion->getUser(196);
    $apoderado = $conciliacion->personasPorTipo('apoderado')->first();

@endphp
<form id="myFormApoderado">
    <div class="row" id="content_apoderado_solicitud">


        @if ($apoderado)
            <input type="hidden" name="persona_externa_id" value="{{ $apoderado->id }}">
            {{--  @include('myforms.conciliaciones.componentes.formulario_apoderado', [
            'disabled' => !Request::has('id') || $user->idnumber != null ? 'disabled' : '',
        ])  --}}
            @include('myforms.categorias.refs_aditional_data', [
                'data' => $apoderado->persona->preguntas()->orderBy('orden', 'asc')->get(),
                'col' => 3,
                'model' => $apoderado,
                // 'design' => 'card_question',
            ])
        @endif




    </div>




    <div class="row mb-1">
        @if (!$user->idnumber != null)
            <div class="col-md-12">
                <h4>
                    Marque la siguiente casilla en caso de no contar con un apoderado
                    <input class="chk_not_parte" id="chk_not_parte_apoderado" type="checkbox">
                </h4>
            </div>
        @endif
    </div>

</form>
