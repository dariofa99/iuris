@component('components.b4.modal_large')


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
        <div style="display: none" id="content_infoexp" class="alert alert-warning alert-dismissible fade show" role="alert">
            

            <span id="rl_user_solicitud">
            </span>

            <span id="lbl_text_casosasig">

            </span>
            <ul id="list_casos_asignados">

            </ul>
        </div>
        <div id="content_user_exp_asig">
            @include('myforms.components_exp.frm_user_register')
        </div>
    @endslot
@endcomponent
<!-- /modal -->
