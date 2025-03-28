@php
    $user = $conciliacion->getUser(197);
@endphp
<div class="card card-outline card-info" id="parte_solicitada">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <h4 align="center"> <strong> PARTE SOLICITADA </strong>
                </h4>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row" id="content_solicitada"
            style="display: {{ $user->idnumber != null ? 'block' : 'none' }};width:100%">

            <div id="user_solicitante_form">
                @include('myforms.conciliaciones.componentes.user_partesolicitada_form')
            </div>
        </div>
    </div>
</div>
