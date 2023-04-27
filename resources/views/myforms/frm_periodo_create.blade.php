@component('components.modal')


	
	
	@slot('trigger')
		myModal_create_periodo
	@endslot 

	@slot('title')
	Creando Periodo
	@endslot

 
	@slot('body')

@section('msg-contenido')
Registrado
@endsection 
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_create_periodo','files' => true,'class'=>""])!!}
		
	<div class="form-group">
    <label for="exampleInputEmail1">Fecha de Inicio</label>
    <input type="date" class="form-control required" name="prdfecha_inicio" id="prdfecha_inicio" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Fecha de Final</label>
    <input type="date" class="form-control required" name="prdfecha_fin" id="prdfecha_fin" >
  </div>
  <div class="form-group">
    <label for="exampleInputFile">Descripción</label>
    <input type="text" class="form-control required" id="prddes_periodo" name="prddes_periodo" placeholder="Periodo 1">
    
  </div>
  <button type="submit"  class="btn btn-primary">Enviar</button>
		
{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










