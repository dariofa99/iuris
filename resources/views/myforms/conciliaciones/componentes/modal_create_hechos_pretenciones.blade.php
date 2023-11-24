@component('components.b4.modal_large')
    @slot('trigger')
        myModalCreateConcHechosPretensiones
    @endslot

    @slot('title')
        <label id="lbl_title_modal">Agregando Información</label>
    @endslot


    @slot('body')
       
        <div class="row">
            <div class="col-md-12">
                <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateHechoPretension">
                    <input type="hidden" name="id">
                    <input type="hidden" name="tipo_id">

                    <div id="content_create_descrip_hepr">

                        <div class="form-group count_input_descrip_hepr content_input_descrip_hepr">
                            <label for="description" id="lbl_descrip_hepr">Descripción de los hechos 1</label>
                            <textarea name="descripcion[]" class="form-control" rows="2"></textarea>
                        </div>


                    </div>
                   

                   
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" id="btn_add_he_pret_input" class="btn btn-block btn-success btn-sm">
                                Agregar otro hecho
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <br>
                            <button type="submit" class="btn btn-block btn-primary btn-sm">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    @endslot
@endcomponent
<!-- /modal -->
