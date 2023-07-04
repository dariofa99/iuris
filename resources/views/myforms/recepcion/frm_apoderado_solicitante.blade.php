@php
  $user = $conciliacion->getUser(196);
@endphp
<form id="myFormApoderado">
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
  <div class="row">
    @include('myforms.conciliaciones.componentes.formulario_apoderado',
    [
        "disabled"=>(!Request::has('id') || $user->idnumber!=null) ? "disabled" : ''    ])

    
 
</form>