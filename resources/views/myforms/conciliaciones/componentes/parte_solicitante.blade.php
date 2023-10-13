@php
    $user = $conciliacion->getUser(205);
@endphp

<div class="row">
    <div class="col-md-12">
        <h4 align="center"> <strong> PARTE SOLICITANTE </strong>
            @if (currentUser()->can('ver_asignaciones_conciliacion'))
                @if ($conciliacion->estado_id == 240 || $conciliacion->estado_id == 174 || $conciliacion->estado_id == 176 || $conciliacion->estado_id == 194)
                    @if ($user->idnumber == null || currentUserInConciliacion($conciliacion->id, ['autor', 'solicitante']))
                        <button data-form="form_solicitante" type="button"
                            @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif
                            data-section="solicitante" data-type="205"
                            class="btn btn-primary btn-sm btn_asinar_usuario_conciliacion float-right">
                            <i class="fa fa-plus"> </i> {{ $user->idnumber != null ? 'Actualizar' : 'Agregar' }}
                        </button>
                    @endif

                    @if ($user->idnumber != null)
                        <button type="button" data-user="{{ $user->idnumber }}" data-pivot="{{ $user->pivot->id }}"
                            class="btn btn-danger mr-2 btn-sm btn_delete_usuario_conciliacion float-right">
                            <i class="fa fa-trash"> </i>
                        </button>
                    @endif

                @endif
            @endif
        </h4>

    </div>
</div>
<div class="row" id="form_solicitante">
    <div class="col-md-12" id="ctbotones-205" style="display: none">
        <button data-form="form_solicitante" style="margin: 1px" type="button"
            @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif data-type="205"
            class="btn btn-default btn-sm btn_cancel_usuario_conciliacion float-right">
            Cancelar
        </button>

        <button data-form="myUserSolicitanteForm" style="margin: 1px" type="button"
            @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif data-section="solicitante"
            data-type="205" class="btn btn-success btn-sm btn_agregar_usuario_conciliacion float-right">
            <i class="fa fa-plus"> </i> {{ $user->idnumber != null ? 'Actualizar' : 'Agregar' }}
        </button>
    </div>
    <div id="user_conciliacion_form" style="width: 100%">
        @include('myforms.conciliaciones.componentes.user_solicitante_form', ['user' => $user, 'col' => 4])
    </div>
</div>
