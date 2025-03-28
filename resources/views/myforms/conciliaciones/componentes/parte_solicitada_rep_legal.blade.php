@php
    $user = $conciliacion->getUser($tipo_usuario_id);
@endphp
<div class="card card-outline card-info" id="parte_solicitada_rep_legal">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <h4 align="center" style="color: #000000; font-weight: bold;">
                    Representantes legales </h4>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="user_rep_legal_solicitada_form">
            @include('myforms.conciliaciones.componentes.user_replegal_solicitada_form')
        </div>
    </div>
</div>
