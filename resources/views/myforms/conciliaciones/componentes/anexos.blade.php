@php
    $representantes_legales = $conciliacion->personasPorTipo('representante_legal')->get();

@endphp



<div class="card card-outline card-info" id="anexos_conciliacion">
    <div class="card-header">
        {{--  <div class="row">
            <div class="col-md-12">
                <h4>Anexos</h4>
                @if ($conciliacion->estado_id == 174 || $conciliacion->estado_id == 176 || $conciliacion->estado_id == 194 || ($conciliacion->estado_id == 240 and currentUserInConciliacion($conciliacion->id, ['autor', 'solicitante'])))
                    @if (currentUser()->hasRole('diradmin') || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai') || currentUserInConciliacion($conciliacion->id, ['autor', 'solicitante']))
                        <button type="button" data-category="232" id="btn_create_document"
                            class="btn_create_document btn btn-primary btn-sm float-right">Subir documentos
                        </button>
                    @endif
                @endif
            </div>
        </div> --}}
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive no-padding">
                    <table class="table" id="table_required_anexos">
                        <tr class="border-left-4 border-left-info files" data-required="true" data-label="Documento de identidad" id="row_doc_identidad">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-id-card text-info mr-2" style="font-size: 1.2rem;"></i>
                                    <span class="font-weight-500">Documento de identidad</span>
                                </div>
                            </td>
                            <td class="align-middle">-</td>
                            <td class="align-middle">-</td>
                            <td class="align-middle text-right">
                                <button class="btn btn-sm btn-info btn_add_document" data-type="documento_identidad"
                                    data-category="233" title="Subir documento de identidad">
                                    <i class="fas fa-cloud-upload-alt"></i> Subir
                                </button>
                            </td>
                        </tr>
                        @if ($representantes_legales->count() > 0)
                            <tr class="border-left-4 border-left-warning" data-required="true" data-label="Certificado de existencia jurídica"  id="row_certificado_existencia">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-certificate text-warning mr-2" style="font-size: 1.2rem;"></i>
                                        <span class="font-weight-500">Certificado de existencia jurídica</span>
                                    </div>
                                </td>
                                <td class="align-middle">-</td>
                                <td class="align-middle">-</td>
                                <td class="align-middle text-right">
                                    <button class="btn btn-sm btn-warning btn_add_document"
                                        data-type="certificado_existencia" data-category="233"
                                        title="Subir certificado de existencia jurídica">
                                        <i class="fas fa-cloud-upload-alt"></i> Subir
                                    </button>
                                </td>
                            </tr>
                        @endif
                        <tr class="border-left-4 border-left-success" data-required="true" data-label="Soporte de evaluación socioeconómica" id="row_eva_socioeconomica">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-alt text-success mr-2" style="font-size: 1.2rem;"></i>
                                    <span class="font-weight-500">Soporte de evaluación socioeconómica</span>
                                </div>
                            </td>
                            <td class="align-middle">-</td>
                            <td class="align-middle">-</td>
                            <td class="align-middle text-right">
                                <button class="btn btn-sm btn-success btn_add_document" data-type="eva_socieconomica"
                                    data-category="233" title="Subir soporte de evaluación">
                                    <i class="fas fa-cloud-upload-alt"></i> Subir
                                </button>
                            </td>
                        </tr>
                        <tr class="border-left-4 border-left-secondary" data-required="false" data-label="Otros documentos" id="row_otros_documentos">
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-folder-open text-secondary mr-2" style="font-size: 1.2rem;"></i>
                                    <span class="font-weight-500">Otros documentos</span>
                                </div>
                            </td>
                            <td class="align-middle">-</td>
                            <td class="align-middle">-</td>
                            <td class="align-middle text-right">
                                <button class="btn btn-sm btn-secondary btn_add_document" data-type="otros_documentos"
                                    data-category="232" title="Subir otros documentos">
                                    <i class="fas fa-cloud-upload-alt"></i> Subir
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="table-responsive no-padding">


                    <table class="table table-bordered table-striped" id="table_anexos">
                        <thead>
                            <tr>
                                <th>📌 Concepto</th>
                                <th>📄 Nombre del archivo</th>
                                <th>👤 Usuario</th>
                                <th>⚡ Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @include('myforms.conciliaciones.componentes.anexos_ajax')

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
