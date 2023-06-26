@component('components.modal')


	
	
	@slot('trigger')
		myModal_edit_periodo
	@endslot 

	@slot('title')
	Actualizando Periodo
	@endslot

  
	@slot('body')

@section('msg-contenido')
Registrado
@endsection 
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_edit_periodo','files' => true,'class'=>""])!!}
	<input type="hidden" class="form-control" name="id_periodo" id="id_periodo">	
	<div class="form-group">
    <label for="prdfecha_inicio">Fecha de Inicio</label>
    <input type="date" class="form-control required" name="prdfecha_inicio" id="prdfecha_inicio" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="prdfecha_fin">Fecha de Final</label>
    <input type="date" class="form-control required" name="prdfecha_fin" id="prdfecha_fin" >
  </div>
  <div class="form-group">
    <label for="prddes_periodo">Descripción</label>
    <input type="text" class="form-control required" id="prddes_periodo" name="prddes_periodo" placeholder="Periodo 1">
    
  </div>
  <button type="submit"  class="btn btn-primary">Enviar</button>
		
{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










