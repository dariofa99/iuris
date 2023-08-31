<form id="myUserApoderadoForm" data-view="user_apoderado_form" data-content="user_apoderado_form">
    <div class="row">
        @include('myforms.conciliaciones.componentes.formulario_apoderado', [
            'disabled' => !Request::has('id') || $user->idnumber != null ? 'disabled' : '',
        ])
    </div>
</form>
