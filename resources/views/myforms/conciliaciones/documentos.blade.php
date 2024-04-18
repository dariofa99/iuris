<div class="row">
    @if(!currentUser()->hasRole('visitante_conciliacion'))
    <div class="col-md-3">
        <button type="button" id="btn_create_document_" data-category="233"
            class="mb-2 btn btn-primary btn-sm  btn_create_document">
            Agregar documento
        </button>
    </div>
    @endif
    <div class="col-md-12">
        <table class="table" id="tablelistardocumentosgen">
            <thead>
                <th>
                    Concepto
                </th>
                <th>
                    Categoría
                </th>
                <th>
                    Creado por
                </th>
                <th>
                    Archivo
                </th>
                <th>
                    Acciones
                </th>
            </thead>
            <tbody>
               @include("myforms.conciliaciones.componentes.documentos_ajax")

            </tbody>
        </table>
    </div>
</div>
