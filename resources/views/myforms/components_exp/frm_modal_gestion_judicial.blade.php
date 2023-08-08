@component('components.b4.modal_medium')
    @slot('trigger')
        myModal_gestion_judicial
    @endslot

    @slot('title')
        Gestión de proceso judicial
    @endslot

    @slot('body')
        <div class="content_detalles_exprocju"></div>
        <div class="content_formulario_exprocju">
            @include('myforms.components_exp.frm_formulario_exprocjudicial')

        </div>
    @endslot
@endcomponent
<!-- /modal -->
