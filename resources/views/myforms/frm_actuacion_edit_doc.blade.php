@component('components.modal')
	
	@slot('trigger')
		myModal_act_edit_doc
	@endslot

	@slot('title')
		Actualizando  Archivo:: <h5><label class="label bg-orange" id="lab-txt-doc">hola</label></h5>	
	@endslot

 
	@slot('body')

{!!Form::open([ 'id'=>'myform_act_edit_doc','files' => true])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden"  id="idact2">


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

<div class="col-md-12">
		    <div class="form-group">
		        {!! form::label('Archivo','Subir archivo')!!}
		        {!! form::file('actdocnomgen',null,['class' => 'form-control','id'=>'docFile']) !!}
		        {!! form::hidden('actdocnompropio','.',['class' => 'form-control']) !!}
		        {!! form::hidden('actdocruta','.',['class' => 'form-control']) !!}
		    </div>
	    </div>


	<div class="col-md-12">
		<div class="form-group">
			<br>
			{!! link_to('#', 'Actualizar', $attributes = array('id'=>'btn_act_edit_doc', 'type'=>'button', 'class'=>'btn btn-primary'), $secure=null)!!}
		</div>
	</div>

	

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










