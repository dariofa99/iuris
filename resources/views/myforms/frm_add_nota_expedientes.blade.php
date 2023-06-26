@component('components.modal')
	
	@slot('trigger')
		myModal_add_nota_expedientes
	@endslot

	@slot('title')
		Agregando Nota:: <h5><label class="label bg-blue" style="font-size: 16px;">
			Primer Corte</label></h5>	
	@endslot

 
	@slot('body')

{!!Form::open([ 'id'=>'myform_add_nota_parcial','files' => true])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden"  id="idact2">


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success') 
 
<div class="row">
	<div class="col-md-4">
						<div class="form-group">
							{!!Form::label('Nota conocimiento') !!}
							{!!Form::text('expnumproc',  null , ['class' => 'form-control','data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
						</div>
					</div>



					<div class="col-md-4">
						<div class="form-group">
							{!!Form::label('Nota aplicación') !!}
							{!!Form::text('exppersondemandante',  null , ['class' => 'form-control','data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
						</div>
					</div>


					<div class="col-md-4">
						<div class="form-group">
							{!!Form::label('Nota Ética') !!}
							{!!Form::text('exppersondemandada',  null , ['class' => 'form-control', 'data-inputmask'=>"'mask': ['9.9']", 'data-mask'=>"" ]); !!}
						</div> 
					</div>
</div>
<div class="row">
	<div class="col-md-12">
						<div class="form-group">
							{!!Form::label('Concepto nota: ') !!}
							{!!Form::textarea('exprtaest',  null , ['class' => 'form-control','maxlength'=>'100000' ]); !!}
						</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
						<button class="btn btn-success">Guardar</button>
	</div>
</div>

	

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










