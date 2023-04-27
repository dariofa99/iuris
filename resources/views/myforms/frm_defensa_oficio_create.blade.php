@extends('layouts.dashboard')


@section('titulo_general')
Expedientes
@endsection

@section('titulo_area')
Listar Expedientes
@endsection


@section('area_forms')

@include('msg.success') 

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@include('myforms.frm_expediente_user_create')			

{!!Form::open(['route'=>'oficio.store', 'method'=>'post','id'=>'myFormExpsStore'])!!}

{!!Form::hidden('periodo_id',$periodo->id) !!}
<div class="row">
	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Código Expediente: ') !!}
			{!!Form::text('expid',null, ['class' => 'form-control', 'required' => 'required', 'maxlength'=>'30'] ); !!}
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Entidad:') !!}
			{!!Form::text('expjuzoent',null, ['class' => 'form-control', 'required' => 'required', 'maxlength'=>'60'] ); !!}
		</div>
	</div>


	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Tipo Proceso: ') !!}
			{!!Form::select('expramaderecho_id',$rama_derecho,null, ['placeholder' => 'Selecciona...', 'class' => 'form-control required', 'required' => 'required','id'=>'expramaderecho_id' ]); !!}
		</div> 
	</div>

</div>


<div class="row">
	<div class="col-md-4">
        <div class="form-group">
          <label>Estudiante</label>
			<select class="form-control selectpicker" data-live-search="true" id="expidnumberest" name="expidnumberest" >
  				  @foreach($users as $key => $user)
					 
							<option value="{{$key}}" data-content="{{$user}} {{--  <span class='label label-success'>S.01</span> <span class='label label-warning'>C.02</span>--}}">{{$user}}</option>
					
						@endforeach
			</select>
              </div>
          </div>

	<div class="col-md-4">
		{!!Form::label('Fecha límite de posesión: ') !!}
	   
	     <div class="input-group">
	      <div class="input-group-addon">
	        <i class="fa fa-calendar"></i>
	      </div>
	      {!!Form::text('expfechalimite', fechaActual(), ['class' => 'form-control required', 'id' => 'expfechalimite', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask' ] ); !!}
	    </div>
	    <!-- /.input group -->
	</div>
      </div>

      <div class="row">
      	<div class="col-md-12">
		<div class="form-group" align="right">
			<br>
			<button class="btn btn-primary">Enviar</button>
		</div>
	</div>
      </div>

	


{!!Form::close()!!}

@stop
