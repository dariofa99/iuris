@component('components.b4.modal_small')
    @slot('trigger')
        myModal_chg_date
    @endslot

    @slot('title')
        Cambiando fecha
    @endslot


    @slot('body')
        @include('msg.ajax.success')
        <div id="content_infoexp">

            <form id="frm_chg_date" method="POST">
                <div class="col-md-12">
                    <label for="newfecha">Cambiar fecha</label>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong>Nota:</strong>
                         Si selecciona la casilla, podrá cambiar la fecha de la evaluación.
                         Si deja en blanco, se tomará la fecha de la última actuación.
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1">
                        <input type="checkbox" name="hability" id="hability">
                    </div>
                    <div class="col-md-11">

                        <input type="date" disabled required class="form-control required form-control-sm" id="newfecha"
                            name="newfecha">


                    </div>
                    <div class="form-group col-md-12 mt-3">

                        <button type="submit" class="btn-block btn btn-primary btn-sm">
                            Guardar
                        </button>

                    </div>
                </div>

            </form>


        </div>
    @endslot
@endcomponent
<!-- /modal -->
