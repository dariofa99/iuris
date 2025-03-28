@php
$user = $conciliacion->getUser(196);

@endphp
<div class="card card-outline card-info p-2" id="apoderado_solicitante">
    <div class="card-header">
        <h4 align="center">
            <strong>APODERADO DE LA PARTE SOLICITANTE</strong>
        </h4>
    </div>
    <div class="card-body" id="form_apoderado">
        <div id="user_apoderado_form" style="width: 100%">
            @include('myforms.conciliaciones.componentes.user_apoderado_form')
         </div>
    </div>
</div>


  

 

