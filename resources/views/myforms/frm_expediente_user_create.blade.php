@component('components.b4.modal_large')

    @slot('cols')
        col-md-8 col-md-offset-2
    @endslot

    @slot('trigger')
        myModal_exp_user_edit
    @endslot

    @slot('title')
        Registro de usuario. <small><i><strong>
                    Los campos marcados con asterisco(*) son obligatorios.</strong></i></small>
    @endslot


    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')
        <div id="content_user_exp_asig">
            @include('myforms.components_exp.frm_user_register')
        </div>
    @endslot
@endcomponent
<!-- /modal -->
