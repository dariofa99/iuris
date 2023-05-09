<form id="myFormApoderado">
<div class="box_section">
    <div class="row">
        <div class="col-md-12">
          <div class="checkbox">
            <label>
              <input {{($conciliacion->getStaticDataVal('informacion_parte_convocada','parte_solicitada')) ? 'checked':''}} id="chk_not_parte_apoderado" type="checkbox">
               No tengo apoderado
            </label>
          </div>
        </div>
      </div>
      <div id="content_apoderado_solicitud">
        @include('myforms.conciliaciones.componentes.apoderado_solicitante',[     
          'section'=>'apoderado_solicitante'
          ])
      </div>
   
</div>
</form>