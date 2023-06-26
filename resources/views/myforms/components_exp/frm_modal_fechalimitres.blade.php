@component('components.modal_dynamic')

	@slot('trigger')
		fechalimitres
	@endslot
	@slot('size')
	modal-dialog modal-md
	@endslot

	@slot('title')
	Modificar fecha límite para respuesta:
	@endslot

 
	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div class="row">
    <div class="col-md-10 col-md-offset-1" id="ct_forcita">
		
			<input type="hidden" class="form-control" id="id" name="id">
			<div class="form-row">
				<div class=" col-md-12">
					<label for="fecha">Estudiante:</label>
					<span id="lbl_nombre_estudiante">{{$expediente->estudiante->name}} {{$expediente->estudiante->lastname}} </span>
				</div>
				<div class=" col-md-12">
					<label for="fecha">No Expediente:</label>
					<span id="lbl_expid">{{$expediente->expid}}</span>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="fecha">Fecha limite respuesta:</label>
					<input type="date" min="{{date('Y-m-d')}}" class="form-control" id="expfecha_res" name="expfecha_res" value="{{$expediente->expfecha_res}}">
				</div>
			</div>

			<div class="form-row row">
				<div class="form-group col-md-12">
					<button type="button" id="btn_mod_expfecha_res" class="btn btn-primary ">Modificar</button>
				</div>
			</div> 
	
	</div> 
</div>

	@endslot
@endcomponent
<!-- /modal -->










