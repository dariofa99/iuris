<div class="row">
    <div class="col-md-10">
        @if (count($conciliacion->expedientes) > 0)
            <h6>
                <i>Número de expediente:
                    <a href="/expedientes/{{ $conciliacion->expedientes[0]->expid }}/edit">
                        {{ $conciliacion->expedientes[0]->expid }}
                    </a></i>
            </h6>
        @endif
    </div>

    @if (currentUserInConciliacion($conciliacion->id, ['conciliador', 'asistente']))
        @php
            $conciliador = $conciliacion->getUser(203);
            $auth_is_con = $conciliador->id == currentUser()->id;
            if (!$auth_is_con) {
                $conciliador = $conciliacion->getUser(204);
                $auth_is_con = $conciliador->id == currentUser()->id;
            }
        @endphp
        <div class="col-md-2">
            <button data-user_estado="230" data-pivot_id="{{ $conciliador->pivot->id }}"
                style="display: block;margin-bottom:1px;" data-estado="{{ $conciliacion->estado_id }}" id="btn_notificarse"
                class="btn btn-success">
                Aceptar (Notificarse)
            </button>
            <button data-user_estado="231" data-pivot_id="{{ $conciliador->pivot->id }}"
                style="display: block;margin-bottom:1px;" data-estado="{{ $conciliacion->estado_id }}"
                id="btn_notificarse_cancelar" class="btn btn-warning">
                NO Aceptar
            </button>
        </div>
    @endif



</div>



<input type="hidden" name="conciliacion_id" id="conciliacion_id" value="{{ $conciliacion->id }}">
<input type="hidden" id="older_value">
<input type="hidden" id="estado_conciliacion_id" value="{{ $conciliacion->estado_id }}">
<div class="box_section">
    @include('myforms.conciliaciones.componentes.parte_solicitante', [
        'section' => 'parte_solicitante',
    ])

</div>
@php
    $parte_solicitante = $conciliacion->getUser(205); //Solicitante
@endphp

@if ($parte_solicitante->tipopers_id == 238)
    <div class="box_section">
        @include('myforms.conciliaciones.componentes.parte_solicitante_rep_legal', [
            'section' => 'representante_legal_solicitante',
            'tipo_usuario_id' => 195,
        ])
    </div>
@endif

<div class="box_section">
    @include('myforms.conciliaciones.componentes.apoderado_solicitante', [
        'section' => 'apoderado_solicitante',
    ])
</div>

<div class="box_section">
    <h4 align="center">
        <strong>ASUNTO A CONCILIAR</strong>
    </h4>
    @include('myforms.conciliaciones.componentes.asunto', [
        'section' => 'elementos_juridicos',
        'disabled' => 'disabled',
    ])
</div>


<div class="box_section">
    @include('myforms.conciliaciones.componentes.parte_solicitada', [
        'section' => 'parte_solicitada',
        'disabled' => 'disabled',
    ])

</div>
@php
    $parte_sol = $conciliacion->getUser(197); //Solicitada
@endphp

@if ($parte_sol->tipopers_id == 238)
    <div class="box_section">
        @include('myforms.conciliaciones.componentes.parte_solicitada_rep_legal', [
            'section' => 'representante_legal_solicitada',
            'tipo_usuario_id' => 198,
        ])
    </div>
@endif
<div class="box_section">
    @include('myforms.conciliaciones.componentes.elementos_juridicos', [
        'section' => 'elementos_juridicos',
    ])

</div>
<div class="box_section">
    @include('myforms.conciliaciones.componentes.anexos', [
        'section' => 'anexos',
    ])
</div>
