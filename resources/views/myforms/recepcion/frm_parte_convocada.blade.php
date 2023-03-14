<form id="myFormParteConvocada" method="POST">
  <div class="box_section">
    <div class="row">
      <div class="col-md-12">
        <div class="checkbox">
          <label>
            <input {{($conciliacion->getStaticDataVal('informacion_parte_convocada','parte_solicitada')) ? 'checked':''}} id="chk_not_parte" type="checkbox">
             No tengo toda la información de la parte convocada
          </label>
        </div>
      </div>
    </div>
    <div class="row" id="content_detalles_solicitada" style="display: {{($conciliacion->getStaticDataVal('informacion_parte_convocada','parte_solicitada')) ? 'block':'none'}}">
      <div class="col-md-12">
        <div class="form-group">
          
          <div class="box_section">
            @include('myforms.conciliaciones.componentes.asunto',[     
            'section'=>'parte_solicitada'
        ])
        </div> 
        {{--   <textarea class="form-control" name="data" id="data"  rows="5" placeholder="Ej. Nombre:Pedro; Teléfono:3212565XXX"></textarea>
      --}} 
      </div>
      </div>
    </div>

    
    <div id="content_solicitada" style="display: {{($conciliacion->getStaticDataVal('informacion_parte_convocada','parte_solicitada')) ? 'none':'block'}}">
      @include('myforms.conciliaciones.componentes.parte_solicitada',[     
        'section'=>'parte_solicitada'
    ])
    </div>
 
     </div>
</form>