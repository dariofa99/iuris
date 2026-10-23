@component('components.b4.modal_extra_large')
    @slot('trigger')
        mymodalShowAlerts
    @endslot
    @slot('size')
        modal-dialog modal-lg
    @endslot

    @slot('title')
        <h3 id="modal_t">Aviso sobre defensas de oficio</h3>
    @endslot

    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')
        
        <div id='modal-show-alerts-content'>


        </div>
    @endslot

    @slot('footer')
        <div id="contentNotButtonDis">

        </div>
    @endslot
@endcomponent
<!-- /modal -->
