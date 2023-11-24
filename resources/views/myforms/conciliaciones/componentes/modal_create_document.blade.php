@component('components.b4.modal_large')
    @slot('trigger')
        myModal_create_document
    @endslot

    @slot('title')
        Agregando anexo
    @endslot


    @slot('body')
        <div class="row" style="display:block;margin-bottom:4px;" id="content_form_support_file_log">
            <div class="col-12">
                <div id="actions_upload_logs">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-primary btn-sm fileinputclickable"  id="Documento de identidad">
                        <i class="fa fa-upload"></i>
                        <span>Subir documento identidad</span>
                    </span>

                    <span class="btn btn-primary btn-sm fileinputclickable" id="Registro">
                        <i class="fa fa-upload"></i>
                        <span>Subir registro</span>
                    </span>

                    <span class="btn btn-primary btn-sm fileinputclickable" id="otro">
                        <i class="fa fa-upload"></i>
                        <span>Subir otro</span>
                    </span>

                    <button type="reset" class="btn btn-sm btn-default cancel">
                        <i class="fa fa-window-close-o"></i>
                        <span>Quitar archivos</span>
                    </button>
                    <button type="reset" class="btn btn-sm btn-success start">
                        <i class="fa fa-upload"></i>
                        <span>Cargar archivos</span>
                    </button>
                </div>

            </div>

            <div class="col-md-12 mt-3" id="cont_files">
                <div class="table table-striped files" id="previews_logs">

                    <div id="template_3" class="file-row" style="display:block">

                        <div class="row">
                            <div class="col-md-3">
                                <span class="preview"><img data-dz-thumbnail /></span>

                            </div>
                            <div class="col-md-4">
                                <div class="dz-filename"><span data-dz-name class=""></span>
                                </div>

                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0"
                                    aria-valuemax="100" aria-valuenow="0">
                                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress>
                                    </div>
                                </div>
                                <p class="size" data-dz-size></p>
                            </div>
                            <div class="col-md-4">
                               <input placeholder="Nombre del archivo"  type="text" readonly class="form-control-dropzone">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-warning cancel">
                                    <i class="fa fa-minus-circle"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row" id="content_list_support_file_log">
            <div class="col-md-12">
                <ul id="log_files" class="products-list product-list-in-card pl-2 pr-2">
                </ul>
            </div>
        </div>

        <div class="row">


            <div class="col-md-12">

                {{--   <form method="POST" class="form_store" accept-charset="UTF-8" id="myformCreateConciliacionAnexo"
                    enctype="multipart/form-data">
                    <input type="hidden" name="file_id">
                    <div class="form-group">
                        <label for="description">Concepto</label>
                        <input type="text" class="form-control " required="" name="concept" id="concept_id">
                    </div>
                    <div class="form-group">
                        <input required="" type="file" name="conciliacion_file" id="conciliacion_file">
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <br>
                            <button type="submit" class="btn btn-block btn-primary btn-sm">
                                Guardar
                            </button>

                            <button type="button" style="display:none" id="btn_cancel_upsoldo"
                                class="btn btn-block btn-default btn-sm">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </form> --}}
            </div>

        </div>
    @endslot
@endcomponent
<!-- /modal -->
