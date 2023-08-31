@php
    $user = $conciliacion->getUser(197);
@endphp
<div class="row">
    <div class="col-md-12">
        <h4 align="center"> <strong> PARTE SOLICITADA </strong>
            @if (currentUser()->hasRole('diradmin') ||
                    currentUser()->hasRole('coord_centro_conciliacion') ||
                    currentUser()->hasRole('amatai') ||
                    currentUserInConciliacion($conciliacion->id, ['autor', 'auxiliar', 'conciliador']))

                @if ($conciliacion->estado_id == 174 || $conciliacion->estado_id == 176 || $conciliacion->estado_id == 194)

                    @if ($user->idnumber == null)
                        <button data-form="content_solicitada" type="button"
                            @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif
                            data-section="solicitada" data-type="197"
                            class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion pull-right">
                            <i class="fa fa-plus"> </i> {{ $user->idnumber != null ? 'Actualizar' : 'Agregar' }}
                        </button>
                    @endif
                    @if ($user->idnumber != null)
                        <button type="button" data-user="{{ $user->idnumber }}" data-pivot="{{ $user->pivot->id }}"
                            class="btn btn-danger btn-sm btn_delete_usuario_conciliacion pull-right">
                            <i class="fa fa-trash"> </i>
                        </button>
                    @endif

                @endif
            @endif
        </h4>

    </div>
</div>

<div class="row" id="content_solicitada"
    style="display: {{ ($user->idnumber != null) ? 'block' : 'none' }};width:100%">
    <div class="col-md-offset-9 col-md-3" id="ctbotones-197" style="display: none">
        <button data-form="content_solicitada" style="margin: 1px" type="button"
            @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif data-type="197"
            class="btn btn-default btn-sm btn_cancel_usuario_conciliacion pull-right">
            Cancelar
        </button>

        <button data-form="myUserParteSolicitadaForm" style="margin: 1px" type="button"
            @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif data-section="rep_legal"
            data-type="197" class="btn btn-success btn-sm btn_agregar_usuario_conciliacion pull-right">
            <i class="fa fa-plus"> </i> {{ $user->idnumber != null ? 'Actualizar' : 'Agregar' }}
        </button>
    </div>
    <div id="user_solicitante_form">
        @include('myforms.conciliaciones.componentes.user_partesolicitada_form')
    </div>
</div>

<div class="row" id="content_detalles_solicitada"
    style="display: {{ ($conciliacion->getStaticDataVal('informacion_parte_convocada', 'parte_solicitada') and $user->idnumber == null) ? 'block' : 'none' }}">
    <div class="col-md-12">
        <div class="form-group">
            @include('myforms.conciliaciones.componentes.asunto', [
                'section' => 'parte_solicitada',
                'col' => 12,
            ])

        </div>
    </div>
</div>
