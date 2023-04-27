@component('components.modal')
	
	@slot('trigger')
		myModal_add_asesoria_docente
	@endslot

	@slot('title')
		Agregando Asesoría	<br>
		Docente: {{ Auth::user()->name }} <br>
		Fecha: {{ date('d-m-Y') }}
	@endslot

 
	@slot('body')

 


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_add_asesoria_docente'])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden" value="{{ $expediente->id }}"  id="idact2">
<div id="formAddAsesoria">

<div class="row">
	<div class="col-md-12">
		<div class="pull-right">
		{!!Form::label('Compartir con estudiante: ') !!}
		<input type="checkbox" id="apl_shared" hidden="hidden" name="apl_shared" checked="true" value="1">
		<i class="fa fa-toggle-on switch-on" id="switch" onclick="changeAplShared()"></i>
	</div>
		 <div class="form-group">
							{!!Form::label('Asesoría docente: ') !!}
							{!!Form::textarea('asesoria_docente',  null , ['class' => 'form-control required','maxlength'=>'100000','id'=>'asesoria_docente' ]); !!}
						</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<input type="button" class="btn btn-primary" value="Enviar" onclick="addAsesoria(this)">
	</div>
</div>
</div>

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










