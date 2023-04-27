@component('components.modal')
	
	@slot('trigger')
		myModal_conciliacion_create
	@endslot

	@slot('title')
		Creando oficina:
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div id="contcreateof">


<form method="POST"  accept-charset="UTF-8" id="myformCreateOficina" enctype="multipart/form-data">

{{csrf_field()}}

<input name="id" type="hidden" id="oficina_id">





	<div class="col-md-12">
		<div class="form-group">
			<label for="nombre">Nombre del estudiante</label>
			<input class="form-control" required  name="nombre" type="text">
		</div>
	</div>



	<div class="col-md-12">
		<div class="form-group">
			<label for="ubi">Dia:</label>
			<input class="form-control" name="ubicacion" required min="{{date('Y-m-d')}}" type="date">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group">
			 <label for="hora">Hora</label>
    <div class="bootstrap-timepicker">
      	<div class="input-group">
			<input type="text" id="hora" name="hora" class="form-control timepicker" value="">
			<div class="input-group-addon">
				<i class="fa fa-clock-o"></i>
			</div>
		</div>
    
   </div>
		</div>
	</div>

	  

    <div class="col-md-6">
		<div class="form-group">
			<br>
			<button type="submit" class="btn btn-block btn-primary btn-sm">
            Guardar
            </button>
            
            	</div>
	</div>



</form>
</div>
	@endslot
@endcomponent
<!-- /modal -->










