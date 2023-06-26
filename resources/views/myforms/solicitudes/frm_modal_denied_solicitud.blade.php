@component('components.modal')
	
	@slot('trigger')
		myModal_solicitud_denied
	@endslot

	@slot('title')
		Rechazando solicitud
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div id="contcreateof">


<form method="POST"  accept-charset="UTF-8" id="myformDeniedSolicitud">

{{csrf_field()}}

<input name="solicitud_id" type="hidden">


<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Motivo') !!}			
			<select name="type_category_id" id="type_category_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($categories as $key => $value)
				<option {{$key == $solicitud->tipodoc_id ? 'selected':''}} value="{{$key}}">{{$value}}</option>
				@endforeach
			</select>
		</div>
	</div>


	<div class="col-md-12">
	<div class="form-group"><label for="description">Mensaje para el usuario</label>
        <textarea name="mensaje" id="mensaje" class="form-control" rows="6" required></textarea>
    </div>
	</div>



	
	  

    <div class="col-md-12">
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










