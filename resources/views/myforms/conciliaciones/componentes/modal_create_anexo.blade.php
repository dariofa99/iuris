@component('components.b4.modal_medium')
    @slot('trigger')
        myModal_form_create_anexo
    @endslot

    @slot('title')
        <h4>
            <i class="fas fa-cloud-upload-alt mr-2 text-primary"></i>Cargar Documento
        </h4>
    @endslot

    @slot('body')
        <!-- Tipo de Documento -->
        <div class="mb-4">
            <label class="form-label font-weight-bold mb-3">
                <i class="fas fa-file-invoice mr-2 text-info"></i>Selecciona el tipo de documento
            </label>
            <div class="btn-group-vertical w-100" role="group">
                <label id="radio_doc_identidad"
                    class="radio_doc btn btn-outline-info text-left p-3 border-left-4 border-left-info"
                    style="border-radius: 0.3rem; cursor: pointer;">
                    <input type="radio" name="docType" value="identidad" class="mr-2">
                    <i class="fas fa-id-card mr-2"></i><strong>Documento de identidad</strong>
                    <small class="d-block text-muted mt-1">Cédula, pasaporte o documento de identidad</small>
                </label>
                <label id="radio_certificado_existencia"
                    class="radio_doc btn btn-outline-warning text-left p-3 border-left-4 border-left-warning mt-2"
                    style="border-radius: 0.3rem; cursor: pointer;">
                    <input type="radio" name="docType" value="existencia" class="mr-2">
                    <i class="fas fa-certificate mr-2"></i><strong>Certificado de existencia jurídica</strong>
                    <small class="d-block text-muted mt-1">Certificado de cámara de comercio</small>
                </label>
                <label id="radio_socieconomica"
                    class="radio_doc btn btn-outline-success text-left p-3 border-left-4 border-left-success mt-2"
                    style="border-radius: 0.3rem; cursor: pointer;">
                    <input type="radio" name="docType" value="socieconomica" class="mr-2">
                    <i class="fas fa-file-alt mr-2"></i><strong>Soporte evaluación socioeconómica</strong>
                    <small class="d-block text-muted mt-1">Documento de estrato, servicios o ingresos</small>
                </label>
                <label id="radio_otros"
                    class="radio_doc btn btn-outline-secondary text-left p-3 border-left-4 border-left-secondary mt-2"
                    style="border-radius: 0.3rem; cursor: pointer;">
                    <input type="radio" name="docType" value="otros" class="mr-2">
                    <i class="fas fa-folder-open mr-2"></i><strong>Otros documentos</strong>
                    <small class="d-block text-muted mt-1">Cualquier otro documento relevante</small>
                </label>
            </div>
        </div>

        <hr class="my-4">

        <!-- Drag & Drop Area -->
        <div class="mb-4" id="form_add_document">
            <label class="form-label font-weight-bold mb-3">
                <i class="fas fa-upload mr-2 text-success"></i>Carga tu archivo
            </label>
            <div class="card border-2 border-dashed p-5 text-center" id="dropZone"
                style="border-color: #e0e0e0; cursor: pointer; transition: all 0.3s ease; background-color: #fafafa;">

                <div style="font-size: 3rem; color: #007bff; margin-bottom: 1rem;">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h5 class="font-weight-bold mb-2">Haz clic para seleccionar</h5>
                {{-- <p class="text-muted mb-3">o haz clic para seleccionar</p> --}}
                <input type="file" id="fileInput" class="d-none" accept=".pdf" />
                <small class="text-muted d-block">
                    <strong>Formatos:</strong> PDF <strong>Máx:</strong> 10 MB
                </small>
            </div>

            <input type="text" name="concept" id="concept">


            <div class="card border-2 border-dashed p-5 text-center" id="selectedZone"
                style="border-color: #e0e0e0; cursor: pointer; transition: all 0.3s ease; background-color: #fafafa;">

                <div id="filePreview" class="d-none mb-4">
                    <div class="border-left-4 border-left-info">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-check-circle text-info mr-2"></i>
                                <strong>Archivo seleccionado:</strong>
                                <br>
                                <span id="fileName" class="font-weight-bold text-dark"></span>
                                <br>
                                <small class="text-muted" id="fileSize"></small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile"
                                style="height: fit-content;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>


        </div>

        <!-- File Preview -->


        <!-- Error Message -->
        <div id="fileError" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span id="errorMessage"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Progress Bar -->
        <div id="uploadProgress" class="d-none mb-4">
            <div class="progress" style="height: 25px;">
                <div id="progressbarwait" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                    role="progressbar" style="width: 0%">
                    <span id="progressPercent">0%</span>
                </div>
            </div>
            <small class="text-muted d-block mt-2">Cargando archivo...</small>
        </div>

        <div>
            <button class="btn btn-primary btn-submit-anexo">
                <i class="fas fa-paper-plane mr-2"></i>Enviar Anexo
            </button>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
