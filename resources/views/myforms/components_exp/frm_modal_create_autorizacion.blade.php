@component('components.b4.modal_large')
    @slot('trigger')
        mymodalCreateAutorizacion
    @endslot

    @slot('title')
    @endslot


    @slot('body')
        <div class="row">
            <div class="col-md-12">
                <div class="iuris-form-card">
                    <!-- HEADER MEJORADO -->
                    <div class="iuris-form-header">
                        <div class="iuris-form-title">
                            <div class="iuris-form-icon">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <div>
                                <h5>Solicitud de Autorización</h5>
                                <small>Complete todos los campos requeridos</small>
                            </div>
                        </div>
                        <div class="iuris-periodo">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <span id="fecha_actual"></span>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div class="iuris-form-body">
                        <form id="myformCreateAutorizacion">
                            <input type="hidden" name="id" id="autorizacion_id">

                            @if (currentUser()->hasRole('estudiante') || currentUser()->hasRole('diradmin'))
                                <!-- SECCIÓN: DATOS DEL ESTUDIANTE -->
                                <div class="row" id="row_create_autorizacion">
                                    <div class="col-12 mb-3">
                                        <h6 class="text-muted"
                                            style="font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #006975; padding-bottom: 8px;">
                                            <i class="fas fa-user-graduate mr-2" style="color: #006975;"></i>
                                            Datos del Estudiante
                                        </h6>
                                    </div>

                                    <div class="form-group col-md-8">
                                        <label for="nombre_estudiante">
                                            <i class="fas fa-user mr-1" style="color: #006975;"></i>
                                            Nombre completo
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="nombre_estudiante" name="nombre_estudiante"
                                            placeholder="Ej: Juan Pérez González">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="genero">
                                            <i class="fas fa-venus-mars mr-1" style="color: #006975;"></i>
                                            Género
                                        </label>
                                        <select name="genero" required id="genero"
                                            class="form-control required form-control-sm">
                                            <option value="m">👨 Masculino</option>
                                            <option value="f">👩 Femenino</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="num_identificacion">
                                            <i class="fas fa-id-card mr-1" style="color: #006975;"></i>
                                            Número de identificación
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="num_identificacion" name="num_identificacion" placeholder="Ej: 123456789">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="doc_expedicion">
                                            <i class="fas fa-map-marker-alt mr-1" style="color: #006975;"></i>
                                            Expedida en
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="doc_expedicion" name="doc_expedicion" placeholder="Ciudad de expedición">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="num_carne">
                                            <i class="fas fa-id-badge mr-1" style="color: #006975;"></i>
                                            No. carné estudiantil
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="num_carne" name="num_carne" placeholder="Ej: 2024-001">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="calidad_de">
                                            <i class="fas fa-user-tag mr-1" style="color: #006975;"></i>
                                            Calidad de
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="calidad_de" name="calidad_de" placeholder="Ej: Estudiante regular">
                                    </div>

                                    <!-- SECCIÓN: DATOS DEL PROCESO -->
                                    <div class="col-12 mt-3 mb-3">
                                        <h6 class="text-muted"
                                            style="font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #006975; padding-bottom: 8px;">
                                            <i class="fas fa-gavel mr-2" style="color: #006975;"></i>
                                            Datos del Proceso
                                        </h6>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="tipo_proceso">
                                            <i class="fas fa-balance-scale mr-1" style="color: #006975;"></i>
                                            Tipo de proceso
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="tipo_proceso" name="tipo_proceso" placeholder="Ej: Acción de tutela">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="num_radicado">
                                            <i class="fas fa-hashtag mr-1" style="color: #006975;"></i>
                                            No. de radicado
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="num_radicado" name="num_radicado" placeholder="Ej: 2024-00123">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="juzgado">
                                            <i class="fas fa-university mr-1" style="color: #006975;"></i>
                                            Juzgado
                                        </label>
                                        <input type="text" required class="form-control required form-control-sm"
                                            id="juzgado" name="juzgado" placeholder="Ej: Juzgado Quinto Administrativo">
                                    </div>
                                </div>
                            @endif

                            <!-- SECCIÓN: CONCEPTO -->
                            <div class="row mt-3">
                                <div class="col-12 mb-3">
                                    <h6 class="text-muted"
                                        style="font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #006975; padding-bottom: 8px;">
                                        <i class="fas fa-pencil-alt mr-2" style="color: #006975;"></i>
                                        Motivo de la Solicitud
                                    </h6>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="concepto">
                                        <i class="fas fa-question-circle mr-1" style="color: #006975;"></i>
                                        ¿Para qué requiere autorización?
                                    </label>
                                    <textarea {{ currentUser()->hasRole('estudiante') ? 'readonly' : '' }} required
                                        class="form-control required form-control-sm" name="concepto" id="concepto" rows="4"
                                        placeholder="Describa detalladamente el motivo de su solicitud de autorización..."
                                        style="resize: vertical; min-height: 100px;"></textarea>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        {{ currentUser()->hasRole('estudiante') ? 'Este campo es de solo lectura' : 'Describa claramente el motivo' }}
                                    </small>
                                </div>

                                <div class="form-group col-md-12">
                                    <small class="text-muted">
                                        <i class="fas fa-user-edit"></i>
                                        Creado por: <span id="lbl_autor_create"
                                            style="font-weight: 600; color: #006975;"></span>
                                    </small>
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="button" id="btn_create_autorizacion" class="btn-iuris-primary">
                                        <i class="fas fa-save mr-2"></i>
                                        Crear Solicitud
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- FOOTER -->
                    <div class="iuris-form-footer">
                        <div class="iuris-required">
                            <i class="fas fa-asterisk" style="color: #dc3545; font-size: 10px;"></i>
                            Campos obligatorios
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
