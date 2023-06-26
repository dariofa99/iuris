@component('components.modal')
	
	@slot('trigger')
	mymodalCreateAutorizacion
	@endslot

	@slot('title')
		Autorizaciones:
	@endslot

 
	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

<div class="row">
<div class="col-md-12">
<form id="myformCreateAutorizacion">
<input type="hidden" name="id" id="autorizacion_id">
<div class="row">
  <div class="form-group col-md-8">
    <label for="nombre_estudiante">Nombre del estudiante</label>
    <input type="text" required class="form-control form-control-sm" id="nombre_estudiante" name="nombre_estudiante">
    
  </div>
   <div class="form-group col-md-4">
    <label for="nombre_estudiante">Género</label>
    <select name="genero" required id="genero" class="form-control form-control-sm">
    <option value="m">Masculino</option>
    <option value="f">Femenino</option>
    </select>
  </div>
  <div class="form-group col-md-6">
    <label for="num_identificacion">Número de identificación</label>
    <input type="text" required class="form-control form-control-sm" id="num_identificacion" name="num_identificacion">
  </div>

    <div class="form-group col-md-6">
    <label for="doc_expedicion">Expedida en</label>
    <input type="text" required class="form-control form-control-sm" id="doc_expedicion" name="doc_expedicion">
  </div>

  <div class="form-group col-md-6">
    <label for="num_carne">No. carné estudiantil</label>
    <input type="text" required class="form-control form-control-sm" id="num_carne" name="num_carne">
   </div>
   
  <div class="form-group col-md-6">
    <label for="calidad_de">Calidad de</label>
    <input type="text" required class="form-control form-control-sm" id="calidad_de" name="calidad_de"   >
  </div>

   <div class="form-group col-md-6">
    <label for="tipo_proceso">Tipo de proceso</label>
    <input type="text" required class="form-control form-control-sm" id="tipo_proceso" name="tipo_proceso" >
   </div>
   
  <div class="form-group col-md-6">
    <label for="num_radicado">No. de radicado</label>
    <input type="text" required class="form-control form-control-sm" id="num_radicado" name="num_radicado" >
  </div>

   
  <div class="form-group col-md-12">
    <label for="juzgado">Juzgado</label>
    <input type="text" required class="form-control form-control-sm" id="juzgado" name="juzgado" >
  </div>

 <div class="form-group col-md-12">
     <button type="submit" class="btn btn-primary btn-block">Crear</button>
  </div>


  </div>
 
</form>
</div>
</div>
	@endslot
@endcomponent
<!-- /modal -->










