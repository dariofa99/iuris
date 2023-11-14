@component('components.b4.modal_medium')
    @slot('trigger')
        mymodalPausasExpediente
    @endslot

    @slot('title')
        Pausas del expediente
    @endslot


    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')

        <div class="row">
            <div class="col-md-12">
                <table class="table" id="tblListPausasExp">
                    <thead>
                        <th>
                            No
                        </th>
                        <th>
                            Fecha de inicio
                        </th>
                        <th>
                            Fecha de fin
                        </th>
                        <th>
                            Acciones
                        </th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
