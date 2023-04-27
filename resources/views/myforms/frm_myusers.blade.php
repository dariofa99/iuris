@extends('layouts.dashboard')
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

{!!Form::open(['route'=>'users.store', 'method'=>'post'])!!}

	<div class="col-md-12">
		<div class="form-group" align="right">	
			{!! Form::hidden('active', '0') !!}		
            {!! Form::checkbox('active', '1') !!}
            {!!Form::label('Usuario Activo ') !!}                 
        </div>
     </div>    





	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Tipo de documento ') !!}
			{!!Form::select('tipodoc',$tipodoc,null, ['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required' ]); !!}
		</div>  
	</div>




	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Número de Identificación: ') !!}
			{!!Form::text('idnumber', null, ['class' => 'form-control onlynumber', 'data-toggle'=>'tooltip', 'title'=>'Solo números', 'required' => 'required'] ); !!}
		</div>
	</div>



	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Nombres: ') !!}
			{!!Form::text('name', null, ['class' => 'form-control' , 'required' => 'required']); !!}
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Apellidos: ') !!}
			{!!Form::text('lastname', null, ['class' => 'form-control', 'required' => 'required']); !!}
		</div>
	</div>


	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Contraseña: ') !!} 
			 <div class="input-group">
	      <div class="input-group-addon" style="cursor: pointer;" onmousedown="showPassword('password')" onmouseup="showPassword('password')">
	        <i class="fa fa-eye"></i>
	      </div>
	      {!!Form::password('password', ['class' => 'form-control','id'=>'password']); !!}
		  </div>

		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Correo Principal: ') !!}
			{!!Form::text('email', null, ['class' => 'form-control', 'required' => 'required']); !!}
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Tel. Celular: ') !!}
			{!!Form::text('tel1', null, ['class' => 'form-control']); !!}
		</div>
	</div>


	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Telefóno Fijo: ') !!}
			{!!Form::text('tel2', null, ['class' => 'form-control']); !!}
		</div>
	</div>




    <div class="col-md-4">
	    <div class="form-group">
	    	{!!Form::label('Fecha Nacimiento: ') !!}
	     <div class="input-group">
	      <div class="input-group-addon">
	        <i class="fa fa-calendar"></i>
	      </div>
	      {!!Form::text('fechanacimien', null, ['class' => 'form-control', 'required' => 'required','data-inputmask'=>"'alias': 'yyyy/mm/dd'" , 'data-mask'] ); !!}
	    </div>
	    <!-- /.input group -->
	    </div>
    </div>





 	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Estrato ') !!}
			{!!Form::select('estrato_id',$estrato,null,['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required' ]); !!}
		</div> 
	</div>







	{{-- <div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Rol de Usuario: ') !!}
			{!!Form::select('idrol', $roles,

			 null, ['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required' ]); !!}
		</div>
	</div> --}}
 

<div class="col-md-4">
		<div class="form-group">
		{!!Form::label('Rol de Usuario: ') !!}

<select name="idrol[]" id="idrol" multiple  class="form-control selectpicker">
<option value="">Seleccione...</option>
	@foreach ($roles as $key => $rol )
		<option value="{{$key}}">{{$rol}}</option> 
	@endforeach
</select>			 	
			
				</div>
	</div>

	
<!-- 	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Acceso a Oficina Virtual ') !!}
			{!!Form::select('accesofvir', [
										'1' => 'Si',
										'0' => 'No',
																		],

			 null, ['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required' ]); !!}
		</div>
	</div>




		<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Institucion ') !!}
			{!!Form::select('institution', [
										'1' => 'Nariño',
										'2' => 'Mariana',
																		],

			 null, ['placeholder' => 'Selecciona...', 'class' => 'form-control', 'required' => 'required' ]); !!}
		</div>
	</div> -->


<input type="hidden" id='accesofvir' name='accesofvir' value="0">
<input type="hidden" id='institution' name='institution' value="0">




	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Dirección: ') !!}
			{!!Form::text('address', null, ['class' => 'form-control']); !!}
		</div>
	</div>




	<div class="col-md-8">
		<div class="form-group">
			{!!Form::label('Descripción: ') !!}
			{!!Form::text('description', null, ['class' => 'form-control']); !!}
		</div>
	</div>


	





	<div class="col-md-12">
		<div class="form-group" align="right">
			<br>
			<button class="btn btn-primary">Enviar</button>
		</div>
	</div>

{!!Form::close()!!}

@stop
