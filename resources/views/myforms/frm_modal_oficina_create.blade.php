@component('components.modal')
	
	@slot('trigger')
		myModal_oficina_create
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
			<label for="nombre">Nombre</label>
			<input class="form-control" required  name="nombre" type="text">
		</div>
	</div>



	<div class="col-md-12">
		<div class="form-group">
			<label for="ubi">Ubicación </label>
			<input class="form-control" name="ubicacion" required type="text">
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










