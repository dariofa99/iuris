@component('components.b4.modal_large')
    @slot('trigger')
        myModal_exp_user_add
    @endslot

    @slot('title')
        Registro de persona usuaria. <small><i><strong>
                    Los campos marcados con asterisco(*) son obligatorios.</strong></i></small>
    @endslot


    @slot('body')
        @include('msg.ajax.success')
        <div style="display: none" id="content_infoexp" class="alert alert-warning alert-dismissible fade show" role="alert">


            <span id="rl_user_solicitud">
            </span>

            <span id="lbl_text_casosasig">

            </span>
            <ul id="list_casos_asignados">

            </ul>
        </div>
        <div id="content_user_exp_add">
            @include('myforms.components_exp.frm_user_add')
        </div>
    @endslot
@endcomponent
<!-- /modal -->
