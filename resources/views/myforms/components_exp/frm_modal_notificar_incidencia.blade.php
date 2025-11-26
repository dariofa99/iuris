@component('components.b4.modal_large')
    @slot('trigger')
        myModal_notificar_incidencia
    @endslot

    @slot('title')
        <label id="lbl_title_fract">Incidencias</label>
    @endslot


    @slot('body')
        <ul class="nav nav-tabs" id="myTabIncidencias" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab"
                    aria-controls="home" aria-selected="true">Reportar</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab"
                    aria-controls="profile" aria-selected="false">Mis solicitudes</button>
            </li>
         
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <div class="row">
                    <div class="col-md-12">
                        <form id="form_incidencia" class="m-2" enctype="multipart/form-data">

                            <div class="p-3"
                                style="border:1px solid rgb(241, 241, 241);border-radius: 16px;box-shadow: 0 4px 12px rgba(108, 108, 108, 0.1);">
                                <div class="">

                                    <!-- Categoría -->
                                    <div class="form-group mb-4">
                                        <label for="categoria" class="font-weight-bold">Categoría</label>
                                        <select class="form-control" id="categoria" name="categoria_id" required>
                                            <option value="">Seleccione una categoría</option>
                                            @foreach ($categorias_incidencia as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->ref_nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Comentario -->
                                    <div class="form-group mb-4">
                                        <label for="motivo" class="font-weight-bold">Comentario</label>
                                        <textarea maxlength="200" class="form-control" id="motivo" name="motivo" rows="4" required
                                            placeholder="Describe brevemente la incidencia o cambio..." style="border-radius: 10px; padding: 12px;"></textarea>
                                        <small class="form-text text-muted">
                                            Sea lo más específico posible. Ejemplo: “Eliminar nota de cero relacionada a la
                                            actuación
                                            'Demanda X'”. <span class="char_count">0/200</span>
                                        </small>
                                    </div>

                                    <!-- Archivo -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold" id="check_file">📎 Adjuntar archivo (opcional)</label>

                                        <div class="custom-file" style="cursor: pointer; display: none;">
                                            <input style="display: none" type="file" class="custom-file-input" id="archivo"
                                                name="archivo" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            <label style="display: none" class="custom-file-label" for="archivo"
                                                id="archivo_label">Seleccionar
                                                archivo...</label>
                                        </div>

                                        <!-- Preview -->
                                        <div id="preview" class="mt-3 d-none"
                                            style="background:#f1f2f6; padding:10px; border-radius:10px;">
                                            <strong>Archivo seleccionado:</strong> <span id="archivo_nombre"></span>
                                        </div>
                                    </div>

                                    <!-- Botón de envío -->
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary"
                                            style="
                        background: linear-gradient(135deg, #4c6ef5, #5f3dc4);
                        color: white;
                        font-weight: 700;
                        padding: 12px 24px;
                        border: none;
                        border-radius: 12px;
                        cursor: pointer;
                        font-size: 16px;
                        transition: all 0.2s ease-in-out;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    "
                                            onmouseover="this.style.transform='translateY(-2px)'"
                                            onmouseout="this.style.transform='translateY(0)'">
                                            🚀 Enviar notificación
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </form>



                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                    <div class="col-md-12 mt-4 mb-4 table-responsive no-padding"
                        style="border:1px solid rgb(222, 221, 221);border-radius: 5px;">
                        <table class="table table-hover mb-0" id="tbl_incidencias">
                            {{-- <thead class="thead-light">
                        <tr class="text-muted" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">
                            <th scope="col" class="pl-4">#</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Usuario</th> 
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead> --}}
                            <tbody>
                                <!-- Ejemplo de filas dinámicas -->


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
        </div>

    @endslot
@endcomponent
<!-- /modal -->
