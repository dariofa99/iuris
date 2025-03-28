<div class="card card-outline card-info" id="parte_solicitante">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <h4 align="center"> <strong> PARTE SOLICITANTE </strong> </h4>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="row" id="form_solicitante">
            <div class="col-md-12" id="ctbotones-205" style="display: none">
                <button data-form="form_solicitante" style="margin: 1px" type="button"
                    @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif data-type="205"
                    class="btn btn-default btn-sm btn_cancel_usuario_conciliacion float-right">
                    Cancelar
                </button>

                <button data-form="myUserSolicitanteForm" style="margin: 1px" type="button"
                    @if ($user->idnumber != null) data-user="{{ $user->idnumber }}" @endif
                    data-section="solicitante" data-type="205"
                    class="btn btn-success btn-sm btn_agregar_usuario_conciliacion float-right">
                    <i class="fa fa-plus"> </i> {{ $user->idnumber != null ? 'Actualizar' : 'Agregar' }}
                </button>
            </div>
            <div id="user_conciliacion_form" style="width: 100%">
                @include('myforms.conciliaciones.componentes.user_solicitante_form', ['col' => 4])
            </div>
        </div>
    </div>
</div>
