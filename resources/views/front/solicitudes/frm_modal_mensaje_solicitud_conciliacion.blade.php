@component('components.b4.modal_extra_large')
    @slot('trigger')
        myModal_mensaje_solicitud_conciliacion
    @endslot

    @slot('title')
        
    @endslot


    @slot('body')
        @include('myforms.recepcion.terminos_conciliacion_message')
    @endslot
@endcomponent
<!-- /modal -->
