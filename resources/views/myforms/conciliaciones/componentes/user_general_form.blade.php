 <form id="myUserConciliacionesForm" data-view="user_general_form" data-content="user_gen_conciliacion_form">
        @include('myforms.users.formulario_registro',
        [
                "disabled"=> (isset($user) and $user !=null) ? 'disabled':''
        ])
        @include('myforms.components_user.identitaria',
        [
                "disabled"=> (isset($user) and $user !=null) ? 'disabled':''
        ])
        @include('myforms.components_user.socioeconomica',
        [
                "disabled"=> (isset($user) and $user !=null) ? 'disabled':''
        ]) 
</form>