@component('components.modal')
	
	@slot('trigger')
		myModal_act_edit
	@endslot

	@slot('title')
		Editar
	@endslot

 
	@slot('body')


{!!Form::open([ 'id'=>'myform_act_edit','files' => true])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden"  id="idact">


@section('msg-contenido') 
Registrado
@endsection
@include('msg.ajax.success')


					

			


	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Código expediente') !!}
			{!!Form::text('actexpid',$expediente->expid , ['id' => 'actexpid', 'class' => 'form-control', 'readonly' ]); !!}
		</div>
	</div>


{{-- 
	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Estado del caso') !!}
			 {!!Form::select('actestado',$act_estados,null, ['id'=>'actestado','placeholder' => 'Selecciona...', 'class' => 'form-control', 'readonly', 'disabled' ]); !!} 
			
		</div>
	</div>  --}}


	<div class="col-sm-6">
		{!!Form::label('Fecha creación: ') !!}
		  
		<div class="input-group">
		      <div class="input-group-addon">
		        <i class="fa fa-calendar"></i>
		      </div>
		      {!!Form::text('actfecha', fechaActual(), ['id'=>'actfecha', 'class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask', 'readonly' ] ); !!}
		</div>
		 <!-- /.input group -->
	</div>




	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Actuación') !!} 
			{!!Form::text('actnombre',  null , ['id'=>'actnombre_edit', 'class' => 'form-control required','maxlength'=>'225' ]); !!}
		</div>
	</div>

@if(currentUser()->hasRole('docente'))
			<div class="col-md-12">
				<div class="form-group">
					{!!Form::label('fecha_limit','Fecha limite de entrega',['id'=>'lbl_type_act']) !!}
					{!!Form::date('fecha_limit',  null , ['class' => 'form-control required','maxlength'=>'60','id'=>'fecha_limite' ]) !!}
				</div>
			</div>
@endif

	<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Descripción: ') !!}
			{!!Form::textarea('actdescrip',  null , ['id'=>'actdescrip','class' => 'form-control required','maxlength'=>'225', 'rows' => 5 ]); !!}
		</div> 
	</div>

<div class="col-md-12">
		    <div class="form-group">
		        {!! form::label('Archivo','Subir archivo')!!}
		        {!! form::file('actdocnomgen',null,['class' => 'form-control required','id'=>'actdocnomgen']) !!}
		       <label for="" class="lab_doc_file_est"><i></i></label> 
		    </div>
	    </div>



	<div class="col-md-12">
		<div class="form-group">
			<br>
			{!! link_to('#', 'Actualizar', $attributes = array('onclick'=>'updateActuacion()','id'=>'btn_act_edit', 'type'=>'button', 'class'=>'btn btn-primary'), $secure=null)!!}
		</div>
	</div>

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










