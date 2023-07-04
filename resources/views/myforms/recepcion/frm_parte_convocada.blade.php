@php
$user = $conciliacion->getUser(197);
@endphp
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
   
    <div class="row" id="content_solicitada" >
      
            @include('myforms.conciliaciones.componentes.formulario_parte_solicitada',[
              'disabled'=>''     
            ])
           </div>
  <div class="row" id="content_detalles_solicitada" style="display: {{($conciliacion->getStaticDataVal('informacion_parte_convocada','parte_solicitada') and $user->idnumber==null) ? 'block':'none'}}">
      <div class="col-md-12">
        <div class="form-group">         
            @include('myforms.conciliaciones.componentes.asunto',[     
            'section'=>'parte_solicitada',
            'col'=>12,      
            'disabled'=>''     
        ])
       
      </div>
      </div>  
     </div> 
</form> 