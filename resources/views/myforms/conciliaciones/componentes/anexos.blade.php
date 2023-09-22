<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Anexos</label>
        </div>
    </div>
    <div class="col-md-12">
        @if (
            $conciliacion->estado_id == 174 ||
                $conciliacion->estado_id == 176 ||
                $conciliacion->estado_id == 194 ||
                ($conciliacion->estado_id == 240 and currentUserInConciliacion($conciliacion->id, ['autor'])))
            @if (currentUser()->hasRole('diradmin') ||
                    currentUser()->hasRole('coord_centro_conciliacion') ||
                    currentUser()->hasRole('amatai') ||
                    currentUserInConciliacion($conciliacion->id, ['autor', 'auxiliar']))
                <button type="button" data-category="232" id="btn_create_document"
                    class="btn_create_document btn btn-primary btn-sm float-right">Subir cédula
                </button>
                <button type="button" data-category="232" id="btn_create_document"
                    class="btn_create_document mr-1 btn btn-primary btn-sm float-right">Subir pruebas
                </button>               
            @endif
        @endif

    </div>
</div>
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
                @include('myforms.conciliaciones.componentes.anexos_ajax', [
                    'category' => 232,
                ])
            </tbody>
        </table>
    </div>
</div>
