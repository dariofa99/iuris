@component('components.b4.modal_large')
    @slot('cols')
        col-md-8 col-md-offset-2
    @endslot

    @slot('trigger')
        myModal_exp_user_details
    @endslot

    @slot('title')
        Detalles Usuario
    @endslot


    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')
        @php
            if (currentUser()->hasRole('estudiante') || currentUser()->hasRole('coordprac')) {
                $disabled = '';
            } else {
                $disabled = 'disabled';
            }
        @endphp
        <form id="{{ isset($user) ? 'myFormUserEditExpediente' : 'myFormUserCreateExpediente' }}" method="POST">
            <div class="row">
                @include('myforms.users.formulario_registro', [
                    'user' => $expediente->solicitante,
                ])
                @include('myforms.components_user.discapacidad', [
                    'user' => $expediente->solicitante,
                ])
                @include('myforms.components_user.identitaria', [
                    'user' => $expediente->solicitante,
                ])
                @include('myforms.components_user.socioeconomica', [
                    'user' => $expediente->solicitante,
                ])
            </div>
        </form>
    @endslot
@endcomponent
<!-- /modal -->
