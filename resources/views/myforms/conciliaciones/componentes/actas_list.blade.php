<div class="row">
    <div class="col-md-2">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"
                aria-controls="v-pills-home" aria-selected="true">
                Actas sin activar
            </a>
           <a class="nav-link" id="v-pills-actas-tab" data-toggle="pill" href="#v-pills-actas" role="tab"
                aria-controls="v-pills-actas" aria-selected="false">Actas para generar</a>
           
        </div>
    </div>
    <div class="col-md-10">
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Actas disponibles para activar en el estado actual</h3>
                        <table class="table" id="tbl_listActForStatus">
                            <thead>
                                <tr>
                                    <th>
                                        Nombre del acta
                                    </th>
                                    <th width="20%" colspan="5">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
        
                            </tbody>
                        </table>
                    </div>
                </div>             
            </div>
            <div class="tab-pane fade" id="v-pills-actas" role="tabpanel" aria-labelledby="v-pills-actas-tab">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Actas disponibles para generar</h3>
                        <table class="table" id="tblListarActasCreadas">
                            <thead>
                                <th>
                                    Nombre del acta
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
            </div>

           
            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                <div class="row" id="content_files_conciliacion" style="display: block">
                   {{--  <div class="col-md-2">

                        <button type="button" id="btn_create_document_" data-category="233"
                            class="mb-2 btn btn-primary btn-sm  btn_create_document">
                            Agregar documento
                        </button>

                    </div> --}}

                    <div class="col-md-1 col-md-offset-10">
                        @if (currentUser()->hasRole('diradmin') || currentUser()->hasRole('amatai') || currentUser()->hasRole('secretaria'))
                            {{--  @if ($conciliacion->estado_id == 194 || $conciliacion->estado_id == 225)       
                        <button id="btn_radicar_conci" class="btn btn-success" data-estado="178">Radicar</button>
                        @endif --}}
                        @endif
                    </div>


                    <div class="col-md-12">
                        <table class="table" id="myReportPdfListPrincipal">
                            <thead>
                                <th>
                                    Concepto
                                </th>
                                <th>
                                    Archivo
                                </th>
                                <th>
                                    Subido por
                                </th>
                                <th>
                                    Acciones
                                </th>
                            </thead>
                            <tbody>
                                @include('myforms.conciliaciones.componentes.anexos_ajax', [
                                    'category' => 233,
                                ])
                            </tbody>
                        </table>
                    </div>


                </div>

               {{--  <div class="row" id="content_mail_notificacion_conciliacion" style="display: block">
                    <div class="col-md-12">
                        <h4>
                            Notificando
                        </h4>
                    </div>
                    <div class="col-md-9" id="">
                        <form id="myFormEnviarCorreoConciliacion">
                            <input type="hidden" name="cuerpo_correo">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">Destinatario</div>

                                    <select required name="correo_send[]" class="selectpicker form-control" multiple>
                                        @foreach ($conciliacion->getUsersByType(196) as $key => $user)
                                            <option selected value="{{ $user->email }}">{{ $user->email }}</option>
                                        @endforeach
                                    </select>

                                  
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cuerpo_correo">Cuerpo del correo</label>
                                <div id="content_form_correo" class="summernote">

                                </div>
                                 <div contentEditable="true" id="content_form_correo" class="form-control editable">
                                    
                                </div> 
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>
                </div> --}}
            </div>
            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
               {{--  <table class="table" id="tblListarActasCreadas">
                    <thead>
                        <tr>
                            <th>
                                Nombre
                            </th>
                            <th width="20%" colspan="5">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table> --}}
            </div>
        </div>
    </div>
</div>

