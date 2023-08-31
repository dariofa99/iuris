<form id="myUserSolicitanteForm" data-view="user_solicitante_form" data-content="user_conciliacion_form">
    <div class="row">
        @include('myforms.users.formulario_registro',["disabled"=>'disabled'])
        @include('myforms.components_user.identitaria',["disabled"=>'disabled'])
        @include('myforms.components_user.socioeconomica',["disabled"=>'disabled'])
    </div>

</form> 