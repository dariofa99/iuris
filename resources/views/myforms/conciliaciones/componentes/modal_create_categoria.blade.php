@component('components.modal')
    @slot('trigger')
        myModal_create_category_report
    @endslot

    @slot('title')
        <label id="lbl_modal_title"> Creando categoria</label>
    @endslot


    @slot('body')
        <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateCategoryReport" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="168" name="type_data_id" id="type_data_id">
            <input type="hidden" value="personalizado" name="section" id="section">
            <input type="hidden" value="" name="summernote" id="summernote">

            <div class="form-group">
                <label for="description">Nombre de la categoria</label>
                <input type="text" class="form-control " required name="name" id="name">
            </div>

            <div class="form-group">
                <label for="description">Nombre corto</label>
                <input type="text" readonly class="form-control " required name="short_name" id="short_name">
            </div>

            <div class="form-group">
                <label for="description">Utilizar en</label>
                <select required name="table" id="table" class="form-control">
                    <option value="pdf_reportes">Reportes Conciliaciones</option>
                </select>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-block btn-primary btn-sm">
                        Guardar
                    </button>

                    <button type="button" style="display:none" id="btn_cancel_upsoldoc"
                        class="btn btn-block btn-default btn-sm">
                        Cancelar
                    </button>

                </div>
            </div>
        </form>
    @endslot
@endcomponent
<!-- /modal -->
