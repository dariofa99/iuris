@extends('layouts.dashboard')


@section('titulo_general')
Expedientes
@endsection

@section('titulo_area')
Listar Expedientes
@endsection


@section('area_forms')

@include('msg.success') 




@include('myforms.frm_expediente_user_create')			

{!!Form::open(['route'=>'expedientes.store', 'method'=>'post','id'=>'myFormExpsStore'])!!}
  




	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Código Expediente: ') !!}
			{!!Form::text('expid',$id, ['class' => 'form-control', 'required' => 'required', 'maxlength'=>'12','readonly'] ); !!}
		</div>
	</div>



	



				    <div class="col-md-4">
				    {!!Form::label('Identidicación Solicitante: ') !!}

				    	<div class="input-group">

				                <div class="input-group-btn">

				                  
				                  
				                  {!! Form::button('Agregar', array('class'=>'btn btn-success','data-toggle'=>'modal', 'data-target'=>'#myModal_exp_user_edit', 'id'=>'btn_exp_user_carga_create')) !!}
				                </div>
                			<!-- /btn-group -->
                			{!!Form::text('expidnumber', null , ['class' => 'form-control required', 'required' => 'required' , 'readonly', 'id'=>'expidnumber' ] ); !!}
              			</div>
              		</div>






<!-- 	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Identificación Estudiante: ') !!}
			{!!Form::text('expidnumberest', null, ['class' => 'form-control', 'required' => 'required'] ); !!}
		</div>
	</div>

 -->










	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Rama del Derecho: ') !!}
			{!!Form::select('expramaderecho_id',$rama_derecho,null, ['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required' ]); !!}
		</div> 
	</div>



	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Tipo procedimiento: ') !!}
			<select name="exptipoproce_id" id="exptipoproce_id2" class="form-control" required="">
				<option value="">Selecciona...</option>
				@foreach($tipo_proceso as $tipo_pro)
				
				<option value="{{$tipo_pro->id}}">{{$tipo_pro->ref_tipproceso}}</option>
			
				@endforeach
			</select>
			
		</div>
	</div>




<div class="col-md-4">
    <div class="form-group">
        <label>Estudiante</label>
			<select class="form-control selectpicker estselect1" data-live-search="true" id="expidnumberest" name="expidnumberest" >
				<option value="0000">Primero seleccione el Tipo Procedimiento...</option>
			</select>
    </div>
</div>






<!-- campos ocultos-->

	<div class="col-md-4">
		<div class="form-group">
			 
				
			{!!Form::hidden('exptipocaso_id', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('expdesccorta', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}

            {!!Form::hidden('expestado_id', 1, ['class' => 'form-control'] ); !!}
           @if($periodo)
		   {!!Form::hidden('periodo_id', $periodo->id, ['class' => 'form-control'] ); !!}
		   @endif

            {!!Form::hidden('expdepto_id', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('expmunicipio_id', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('exptipovivien_id', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('expperacargo', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('expingremensual', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('expegremensual', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('exphechos', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('exprtaest', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}

            {!!Form::hidden('expjuzoent', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('expnumproc', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('exppersondemandante', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('exppersondemandada', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
            {!!Form::hidden('exptipoactuacion', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}

            {!!Form::hidden('expfechalimite', null, ['class' => 'form-control', 'required' => 'required' , 'value' => '0'] ); !!}
		</div>
	</div>

<!-- /campos ocultos-->


	<div class="col-md-12">
		<div class="form-group" align="right">
			<br>
			@if($periodo)
			<button class="btn btn-primary">Enviar</button>
			@else
			<button class="btn btn-primary" disabled>No hay un periodo activo</button>
			@endif
		</div>
	</div>


{!!Form::close()!!}

@stop
