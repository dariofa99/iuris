@component('components.modal')
	
	@slot('trigger')
		myModal_exp_user_edit
	@endslot

	@slot('title')
		Editar
	@endslot


	@slot('body')


@section('msg-contenido')
Registrado 
@endsection
@include('msg.ajax.success')

{!!Form::open([ 'id'=>'myform_expediente_user_edit','method'=>'post'])!!}
	@if(isset($conciliacion))
	<input type="hidden" id="conciliciacion" name="conciliacion_id" value="{{$conciliacion->id}}" >
	<input type="hidden" id="type_solicitud_user" name="type_solicitud_user" value="" >
	
	@endif
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden" id="usercreated" name="usercreated" value="{{currentUser()->idnumber}}" >
	<input type="hidden" id="userupdated" name="userupdated" value="{{currentUser()->idnumber}}" >
	<input type="hidden"  name='cursando_id' value="1">
	<input type="hidden" name="genero_id" value="9">
	<input type="hidden" name="estadocivil_id" value="9">
	<input type="hidden" name="estrato_id" value="9">
	{!!Form::hidden('id',  null , ['class' => 'form-control', 'readonly', 'id'=>'id' ]); !!}
	

<div class="row">
	<input type="hidden" name="active" id="active_text" value="0">
	@if(!isset($conciliacion))
	<div class="col-md-12">
		<label for="active">Activo:</label><br>		
		<input type="checkbox" name="active" id="active_us" value="1">
	</div>
	@endif
	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Tipo de documento ') !!}
			
			<select onchange="comprIdnumber()" name="tipodoc_id" id="tipodoc_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($tipodoc as $doc)
				<option value="{{$doc->id}}">{{$doc->ref_nombre}}</option>
				@endforeach
			</select>

					</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Identificación: ') !!}
			{!!Form::text('idnumber',  null  , ['class' => 'form-control onlynumber', 'id'=>'idnumber', 'data-toggle'=>'tooltip', 'title'=>'Solo números', 'required','onBlur'=>'comprIdnumber()']); !!}
		</div> 
	</div>

	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Nombres: ') !!}
			{!!Form::text('name',   null  , ['class' => 'form-control required',  'id'=>'name_us','required']); !!}
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Apellidos: ') !!}
			{!!Form::text('lastname',  null , ['class' => 'form-control required' ,  'id'=>'lastname_us','required']); !!}
		</div> 
	</div>
<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Correo Principal: ') !!}
			{!!Form::text('email', null, ['class' => 'form-control required', 'id'=>'email','required']); !!}
		</div>
	</div>
</div>

@if(!session('sede')->has('ocultar_inputs_form_user'))

<div class="col-md-{{isset($conciliacion) ? '12' : '4'}}">
		<div class="form-group">
			{!!Form::label('Tel. Celular: ') !!}
			{!!Form::text('tel1', null, ['class' => 'form-control', 'id'=>'tel1','required']); !!}
		</div>
	</div>

@if(!isset($conciliacion))
	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Telefóno Fijo: ') !!}
			{!!Form::text('tel2', null, ['class' => 'form-control', 'id'=>'tel2']); !!}
		</div>
	</div>



	<div class="col-md-4">
		<div class="form-group"> 
			{!!Form::label('Orientación Sexual') !!} 

			<select name="genero_id" id="genero_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($genero as $tipo)
				<option value="{{$tipo->id}}">{{$tipo->ref_nombre}}</option>
				@endforeach
			</select>

			
		</div>
	</div> 


	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Estado Civil') !!}
			<select name="estadocivil_id" id="estadocivil_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($estcivil as $tipo)
				<option value="{{$tipo->id}}">{{$tipo->ref_nombre}}</option>
				@endforeach
			</select>
		</div>
	</div>


	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Estrato ') !!}
			<select name="estrato_id" id="estrato_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($estrato as $tipo) 
				<option value="{{$tipo->id}}">{{$tipo->ref_nombre}}</option>
				@endforeach
			</select>
		</div>
	</div>



	


<div class="col-md-12">
		<div class="form-group">

		{!!Form::label('Población Especial ') !!}
			<input type="hidden" id='pbesen_' name='pbesena' value="0" checked="true">
		    <div class="checkbox">
                    <label>
                      {!!Form::checkbox('pbesena', '1', false, ['id'=>'pbesena','class'=>'check-user']);!!} 
                       Sisben
                    </label>
            </div> 



		    <div class="checkbox">
		    	<input type="hidden" id='pbepersondisca_' name='pbepersondiscap' value="0" checked="true">
                    <label>
                      {!!Form::checkbox('pbepersondiscap', '1', false, ['id'=>'pbepersondiscap','class'=>'check-user']);!!} 
                       Persona Discapacitada
                    </label>
            </div>

           
            <div class="checkbox">
            	<input type="hidden" id='pbevictimconflic_' name='pbevictimconflic' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbevictimconflic', '1', false, ['id'=>'pbevictimconflic','class'=>'check-user']);!!} 
                       Victima del desplazamiento y conflicto
                    </label>
            </div>


            
            <div class="checkbox">
            	<input type="hidden" id='pbeadultomayor_' name='pbeadultomayor' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbeadultomayor', '1', false, ['id'=>'pbeadultomayor','class'=>'check-user']);!!} 
                       Adulto Mayor
                    </label>
            </div>

            		   

            <div class="checkbox">
            	<input type="hidden" id='pbeminoetnica_' name='pbeminoetnica' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbeminoetnica', '1', false, ['id'=>'pbeminoetnica','class'=>'check-user']);!!} 
                       Minoria Étnica
                    </label>
            </div>

            


            <div class="checkbox">
            	<input type="hidden" id='pbemadrecomuni_' name='pbemadrecomuni' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbemadrecomuni', '1', false, ['id'=>'pbemadrecomuni','class'=>'check-user']);!!} 
                       Madre Comunitaria
                    </label>
            </div>

            


            <div class="checkbox">
            	<input type="hidden" id='pbecabezaflia_' name='pbecabezaflia' value="0" checked="true">                
                    <label>
                      {!!Form::checkbox('pbecabezaflia', '1', false, ['id'=>'pbecabezaflia','class'=>'check-user']);!!} 
                      Madre o padre cabeza de familia
                    </label>
            </div>

            


            <div class="checkbox">
            	<input type="hidden" id='pbeninguna_' name='pbeninguna' value="0" checked="true">                
                    <label>
                      {!!Form::checkbox('pbeninguna', '1', false, ['id'=>'pbeninguna','class'=>'check-user-dis']);!!} 
                       Ninguna de las anteriores
                    </label>
            </div>


		</div>
	</div>

@endif


<div id="content_aditional_data">
	@include('myforms.components_user.aditional_comp_data')
</div>
@if(!isset($conciliacion))
	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Dirección: ') !!}
			{!!Form::text('address', null, ['class' => 'form-control', 'id'=>'address']); !!}
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Descripción: ') !!}
			{!!Form::text('description', null, ['class' => 'form-control', 'id'=>'description']); !!}
		</div>
	</div>
@endif 
@endif

@if(!isset($conciliacion))
<div class="row">
	<div class="col-md-12">		
		<input type="checkbox" id="active_password" value="1">
		<label for="active">Cambiar contraseña:</label> <i data-toggle="tooltip" title="Se asignará como valor por defecto el número de documento." class="fa fa-question-circle" id=""></i>
	
		<div id="cont_new_pass" style="display:none">
			{!!Form::label('Nueva contraseña: ') !!}
			{!!Form::text('password', null, ['class' => 'form-control', 'id'=>'password_us','disabled']); !!}
		</div>
	</div>
</div>
@endif

	<div class="col-md-12" align="right">
		<div class="form-group">
			<br>
			<button type="submit" style="display: none" class="btn btn-primary btn-block">Actualizar</button>
			<button type="button" id="btn_exp_user_create" class="btn btn-primary btn-block">Crear</button>
			
			{{-- {!! link_to('#', 'Crear', $attributes = array('id'=>'btn_exp_user_create', 'type'=>'button', 'class'=>'btn-block btn btn-primary'), $secure=null)!!}
 --}}
		</div>
	</div> 

<div class="col-md-12" align="right" id="cont_btn_cnu" style="display:none">
		<div class="form-group">
			<br>
					</div>
</div>


	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










