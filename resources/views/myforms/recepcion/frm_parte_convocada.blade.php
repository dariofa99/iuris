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
   
    @include('myforms.conciliaciones.componentes.parte_solicitada',[     
      'section'=>'parte_solicitada',
      'disabled'=>''
      ])
  
     </div>
</form> 