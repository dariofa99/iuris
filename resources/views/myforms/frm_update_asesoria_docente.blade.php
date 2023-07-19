@component('components.b4.modal_medium')
	
	@slot('trigger')
		myModal_update_asesoria_docente
	@endslot

	@slot('title')
	<small>
		Actualizando Asesoría	<hr>
		Docente: {{ Auth::user()->name }} <br>
		Fecha: {{ date('d-m-Y') }}
	</small>

	@endslot

 
	@slot('body')




@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_update_asesoria_docente'])!!}
<input type="hidden" name="id">
<div class="row">
	<div class="col-md-12">
		 <div class="form-group">
							{!!Form::label('Asesoría docente: ') !!}
							{!!Form::textarea('asesoria_docente_update',  null , ['class' => 'form-control required','maxlength'=>'500','id'=>'asesoria_docente_update','rows'=>'5' ]); !!}
						</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<input type="submit" class="btn btn-primary" value="Enviar">
	</div>
</div>


	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










