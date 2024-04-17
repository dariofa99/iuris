@component('components.b4.modal_medium')
    @slot('trigger')
        mymodalPausasExpediente
    @endslot

    @slot('title')
        Pausas del expediente
    @endslot


    @slot('body')
      

        <div class="row">
            <div class="col-md-12 mt-2">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Información importante!</strong><br>
                    Recuerde que si elimina la pausa no se tendrá en
                    cuenta el tiempo pausado y se abrirá nuevamente el caso.
                    Si requiere mantener los días de pausa deberá modificar
                    la fecha final de pausa y dejar que el sistema abra
                    nuevamente el caso. Si requiere terminar inmediatamente la pausa,
                    en editar marque la casilla: <i>
                        Terminar pausa
                    </i>
                    y clic en el botón <i>Actualizar pausa </i>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row" id="tblListPausasExp">

                </div>

            </div>

        </div>
    @endslot
@endcomponent
<!-- /modal -->
