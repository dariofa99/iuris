@component('components.modal')
	
	@slot('trigger')
		myModal_update_asesoria_docente
	@endslot

	@slot('title')
		Actualizando Asesoría	<hr>
		Docente: {{ Auth::user()->name }} <br>
		Fecha: {{ date('d-m-Y') }}
	@endslot

 
	@slot('body')




@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_update_asesoria_docente'])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden" value="{{ $expediente->id }}"  id="asesoria_id" name="asesoria_id">
<div id="formUpdateAsesoria">

<div class="row">
	<div class="col-md-12">
		 <div class="form-group">
							{!!Form::label('Asesoría docente: ') !!}
							{!!Form::textarea('asesoria_docente_update',  null , ['class' => 'form-control required','maxlength'=>'100000','id'=>'asesoria_docente_update' ]); !!}
						</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<input type="button" class="btn btn-primary" value="Enviar" onclick="updateAsesoria(this)">
	</div>
</div>
</div>

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










