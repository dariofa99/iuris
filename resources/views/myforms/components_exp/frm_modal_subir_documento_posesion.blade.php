@component('components.b4.modal_medium')
    @slot('trigger')
        MyModalUploadDocumentoPosesion
    @endslot
    @slot('size')
        modal-dialog modal-md
    @endslot

    @slot('title')
        <span class="iuris-deadline-modal-title">
            <i class="fa fa-file" aria-hidden="true"></i>
            Documento de posesión
        </span>
    @endslot

    @slot('body')
        <div class="iuris-deadline-modal" id="ct_forcita">
            <form>
                <input type="hidden" class="form-control" id="id" name="id">

                <div class="iuris-deadline-modal-summary">
                    <div class="iuris-deadline-modal-person">
                        <span class="iuris-deadline-modal-icon" aria-hidden="true">
                            <i class="fa fa-user"></i>
                        </span>
                        <div>
                            <span class="iuris-deadline-modal-label">Estudiante</span>
                            <strong id="lbl_nombre_estudiante">
                                {{ $expediente->estudiante->name }} {{ $expediente->estudiante->lastname }}
                            </strong>
                        </div>
                    </div>

                    <div class="iuris-deadline-modal-case">
                        <span class="iuris-deadline-modal-label">N.º expediente</span>
                        <strong id="lbl_expid">{{ $expediente->expid }}</strong>
                    </div>
                </div>

                <div class="form-group iuris-deadline-modal-field iuris-document-category">
                    <label for="categoria_documento_posesion">Tipo de documento</label>
                    <div class="iuris-document-category-control">
                        <i class="fas fa-folder-open" aria-hidden="true"></i>
                        <select id="categoria_documento_posesion" name="category_id" class="form-control">
                            @foreach ($cat_documeexp as $categ)
                                <option value="{{ $categ->id }}">{{ $categ->ref_nombre }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down iuris-document-category-chevron" aria-hidden="true"></i>
                    </div>
                    <small>Selecciona la categoría que describe el archivo.</small>
                </div>

                <div class="form-group iuris-deadline-modal-field">
                    <label for="documento_posesion">Documento de posesión</label>
                    <input type="file" id="documento_posesion" name="documento_posesion"
                        class="iuris-possession-upload-input" hidden>
                    <label for="documento_posesion" class="iuris-possession-upload">
                        <span class="iuris-possession-upload-icon" aria-hidden="true">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                        <span class="iuris-possession-upload-copy">
                            <strong>Selecciona el documento</strong>
                            <span id="documento_posesion_label">Suba el archivo.</span>  
                            {{-- progress --}}   
                            <div class="progress" style="height: 7px; margin-top: 5px;">
                                <div class="progress-bar progressbarwait" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                    aria-valuemin="0" aria-valuemax="100"></div>  
                            </div>         
                        </span>
                        <span class="iuris-possession-upload-action">
                            <i class="fas fa-folder-open" aria-hidden="true"></i>
                            Explorar
                        </span>
                    </label>
                    <small>Sube el documento.</small>
                </div>

                <div class="iuris-deadline-modal-actions">
                    <button type="button" class="btn iuris-deadline-modal-cancel" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" id="btn_subir_documentopose" class="btn iuris-deadline-modal-submit">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        Subir documento
                    </button>
                </div>
            </form>

        </div>
    @endslot
@endcomponent
<!-- /modal -->
