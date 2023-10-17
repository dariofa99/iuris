<div class="row" >
    <div class="col-md-2">
     @if(currentUser()->can('change_status_conciliaciones'))
        <input type="button" value="Cambiar estado" class="btn btn-primary btn-block btn-sm" id="btn_cambiar_estado">
        <input type="button" value="Cancelar" style="display: none" class="btn btn-warning btn-block btn-sm" id="btn_cancelar_estado">
    @endif 
    </div>
<div class="col-md-7">

</div>
    {{-- <div class="col-md-3">
        <input type="button" value="Descargar todos los documentos" class="btn btn-info btn-block btn-sm" id="btn_download_all_pfd">
    </div> --}}
</div>

<div class="row" id="content_form_estado_c" style="display: none">
    <div class="col-md-8 col-md-offset-2" >
        @include('myforms.conciliaciones.componentes.formulario_cambiar_estado')
    </div>
    
</div>

<div class="row" id="content_list_estado_c">
    <div class="col-md-12 table-responsive no-padding">
        <table class="table" id="table_list_estados">
            <thead>
                
                <th>
                    Estado
                </th>
                <th>
                    Descripción
                </th>
                <th>
                    Creado por
                </th>
                <th>
                    Fecha creación
                </th>
                <th>
                    Documentos
                </th>
                <th>
                    Acciones
                </th>
            </thead>
            <tbody>
               @include('myforms.conciliaciones.componentes.conciliacion_estados_ajax')
            </tbody>
        </table>
    </div>
</div>