<!-- Trigger the modal with a button -->

@if(currentUser()->hasRole('amatai') || $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber || currentUser()->hasRole('estudiante'))
@if($expediente->expestado_id <>'2' AND  $expediente->expestado_id <>'4' )

	<div class="col-md-6">
		
		@if($expediente->exptipoproce_id != '1')
			<button type="button" @if(currentUser()->hasRole('docente')) id="btn_new_act_doct" @else id="btn_new_act"  @endif   class="btn btn-primary btn-sm btn_new_act" data-toggle="modal" data-titulo_modal="Nueva actuación" data-target="#myModal_act_create">Nueva Actuación</button>
 		@endif
		{{--  <button type="button" class="btn btn-warning btn-sm" id="btn_nueva_conciliacion">
			Crear conciliación
		</button>  --}}
	</div>
 
@endif 

	 
	<div class="col-md-6">		
			<button type="button" class="btn btn-default btn-sm pull-right btn_new_act" data-toggle="modal" data-target="#myModal_act_create" data-titulo_modal="Nuevo anexo" id="btn_new_anex">Agregar Anexo General</button>
 	</div>


@endif

@component('components.modal')
	
	@slot('trigger')
		myModal_act_create 
	@endslot 

	@slot('title')
		<label id="lbl_title_fract"></label> 
	@endslot 


	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')

{!!Form::open(['method'=>'post', 'id'=>'myformCreateAct'])!!}

					
	<div class="form-group">		
		{!!Form::hidden('actestado_id',   '101' , ['id' => 'actestado_id', 'class' => 'form-control', 'readonly' ]); !!}
	</div>

	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Código expediente') !!}
			{!!Form::text('actexpid',  $expediente->expid , ['id' => 'actexpid', 'class' => 'form-control', 'readonly' ]); !!}
		</div>
	</div>




	<!-- 					<div class="col-md-6">
				<div class="form-group">
					{!!Form::label('Estado de la actuación') !!}
					{!!Form::select('expestado_id', [	
												'1' => 'Enviado a revisión',
												'2' => 'Solicitud de modificaciones',
												'3' => 'Enviado con correcciones',
												'4' => 'Aprobado',



											],

					 null, ['placeholder' => 'Selecciona...', 'class' => 'form-control', 'readonly' ]); !!}
				</div>
			</div> -->


	<div class="col-sm-6"> 
		{!!Form::label('Fecha: ') !!}
		  
		<div class="input-group">
		      <div class="input-group-addon">
		        <i class="fa fa-calendar"></i>
		      </div>
		      {!!Form::text('actfecha', fechaActual(), ['class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask', 'readonly' ] ); !!}
		</div>
		 <!-- /.input group -->
	</div>





			
			<div class="col-md-12">
				<div class="form-group">
					<label id="lbl_type_actuacion">Nueva actuación</label>					
					{!!Form::text('actnombre',  null , ['class' => 'form-control required','maxlength'=>'60' ]); !!}
				</div>
			</div>
@if(currentUser()->hasRole('docente'))
			<div class="col-md-12">
				<div class="form-group">
					{!!Form::label('fecha_limit','Fecha limite de entrega',['id'=>'fecha']) !!}
					{!!Form::date('fecha_limit',  null , ['class' => 'form-control required','maxlength'=>'60' ]) !!}
				</div>
			</div>
@endif


			<div class="col-md-12">
				<div class="form-group">
					{!!Form::label('Descripción: ') !!}
					{!!Form::textarea('actdescrip',  null , ['class' => 'form-control required','maxlength'=>'2000','rows' => 5 ]); !!}
				</div>
			</div>



		<div class="col-md-12">
		    <div class="form-group">
		        {!! form::label('Archivo','Subir archivo')!!}
		        {!! form::file('actdocnomgen',null,['class' => 'form-control required','id'=>'actdocnomgen']) !!}
		        {!! form::hidden('actdocnompropio','.',['class' => 'form-control']) !!}
		        {!! form::hidden('actdocruta','.',['class' => 'form-control']) !!}
		    </div>
	    </div>



		<div class="col-md-12">
			<div class="form-group">
				<br>
				{!! link_to('#', 'Nuevo', $attributes = array('id'=>'btn_enviar', 'type'=>'button', 'class'=>'btn btn-primary','onclick'=>'storeActuacion("myformCreateAct")'), $secure=null)!!}
			</div>
		</div>

	{!!Form::close()!!}


	
	{!!Form::open(['route'=>'actuaciones.store', 'method'=>'post', 'id'=>'myform_cotrol'])!!}

			<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
			<div class="col-md-6">
				<div class="form-group">
					{!!Form::hidden('id_control_list',   $expediente->expid , ['id' => 'id_control_list', 'class' => 'form-control', 'readonly' ]); !!}
				</div>
			</div>
	{!!Form::close()!!} 



	@endslot
@endcomponent
<!-- /modal -->








