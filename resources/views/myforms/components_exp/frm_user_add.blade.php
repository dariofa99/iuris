@php
    if (currentUser()->hasRole('estudiante') || currentUser()->hasRole('coordprac')) {
        $disabled = '';
    } else {
        $disabled = 'disabled';
    }
@endphp
<form id="{{ isset($user) ? 'myFormUserAddEditExpediente' : 'myFormUserAddCreateExpediente' }}" method="POST">
    <div class="row">
        @include('myforms.users.formulario_registro')
        @include('myforms.components_user.discapacidad')
        @include('myforms.components_user.identitaria')
        @include('myforms.components_user.socioeconomica')
    </div>
    @if (currentUser()->hasRole('estudiante') || currentUser()->hasRole('coordprac'))
        <button type="submit" id="{{ isset($user) ? 'actualizar_exp_us_add' : 'registrar_exp_us_add' }}"
            class="btn btn-primary btn-block">{{ isset($user) ? 'Actualizar usuario' : 'Asignar usuario' }}  </button>
    @endif
</form>
