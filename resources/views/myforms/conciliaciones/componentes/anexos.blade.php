<div class="card card-outline card-info" id="anexos_conciliacion">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <h4>Anexos</h4>
                @if (
                    $conciliacion->estado_id == 174 ||
                        $conciliacion->estado_id == 176 ||
                        $conciliacion->estado_id == 194 ||
                        ($conciliacion->estado_id == 240 and currentUserInConciliacion($conciliacion->id, ['autor', 'solicitante'])))
                    @if (currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('coord_centro_conciliacion') ||
                            currentUser()->hasRole('amatai') ||
                            currentUserInConciliacion($conciliacion->id, ['autor', 'solicitante']))
                        <button type="button" data-category="232" id="btn_create_document"
                            class="btn_create_document btn btn-primary btn-sm float-right">Subir documentos
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="table_anexos_list">
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
                        @include('myforms.conciliaciones.componentes.anexos_ajax')
        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



