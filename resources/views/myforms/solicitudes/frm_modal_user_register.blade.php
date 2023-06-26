@component('components.b4.modal_medium')

	@slot('trigger')
		myModal_recp_user_create
	@endslot

	@slot('title')
		<h4><label id="lbl_modal_title">Registra tus datos.</label></h4>
     <span style="margin-left:15px;margin-top: -3px;"></span>
	@endslot


	@slot('body')
{{-- <div class="alert alert-info">
        <h5     style="margin-top: 0px; margin-bottom: 0px;">  </i> <b>Antes de continuar necesitamos que completes la siguiente información!</b><br>
		
		</h5>
</div> --}}

 	<div class="row">
		<div class="col-md-12 " id="content_form_cl">
@include('msg.danger')		       
        
 {!!Form::open([ 'id'=>'myform_recp_user_create'])!!}
	
  
	<input type="hidden"  name="usercreated" value="{{$solicitud->idnumber}}" >
	<input type="hidden"  name="userupdated" value="{{$solicitud->idnumber}}" >
	<input type="hidden" id='accesofvir' name='accesofvir' value="0">
	<input type="hidden" id='institution' name='institution' value="0">
	{{-- <input type="hidden" id='idrol' name='idrol' value="8"> --}}
	<input type="hidden" id='datecreated' name='datecreated' >
	<input type="hidden" id='cursando_id' name='cursando_id' value="1">

	{!!Form::hidden('id',  null , ['class' => 'form-control', 'readonly']); !!}


	 




<div class="row">
<div class="col-md-12">

<fieldset>
    <legend>Registrando usuario</legend>
<div class="col-md-12">
		<div class="form-group">
		<label>Registrar nombre de usuario con:</label><br>
<label class="radio-inline">
  <input type="radio" name="type_register" id="inlineRadio2" value="email" checked>
   Correo electrónico
</label>
<label class="radio-inline">
  <input type="radio" name="type_register" id="inlineRadio3" value="cc"> No. de documento
</label>	</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Nombres: ') !!}
			<input type="text" class="form-control" value="{{ $solicitud->name }}" disabled placeholder="Nombres">
        </div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Apellidos: ') !!}
			<input type="text" class="form-control" value="{{$solicitud->lastname }}" disabled placeholder="Apellidos">
	</div>
	</div>
<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Usuario: ') !!}
			<input id="email_us" type="email" class="form-control" name="user_name" value="{{ old('user_name') }}" required placeholder="Correo electrónico">
        </div>
	</div>

<div class="col-md-12">
		<div class="form-group">
			{!!Form::label('Contraseña: ') !!}
			<div class="input-group">
				<div class="input-group-addon" style="cursor: pointer;" onmousedown="showPassword('password')" onmouseup="showPassword('password')">
					<span class="fa fa-eye"></span>
				</div>
	      			{!!Form::password('password', ['class' => 'form-control','id'=>'password']); !!}
		  	</div>
		</div>
	</div>
  </fieldset>
</div>
</div>

<div class="row">
<div class="col-md-12">

{{-- <fieldset>
    <legend>Información general</legend>
    <div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Tipo de documento ') !!}			
			<select name="tipodoc_id" id="tipodoc_id" class="form-control required" disabled>
				<option value="">Seleccione...</option>
				@foreach($tipodoc as $key => $doc)
				<option {{$key == $solicitud->tipodoc_id ? 'selected':''}} value="{{$key}}">{{$doc}}</option>
				@endforeach
			</select>
		</div>
	</div>

    <div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Identificación: ') !!}
			{!!Form::text('idnumber',  $solicitud->idnumber  , ['class' => 'form-control required', 'id'=>'idnumber', 'onBlur'=>'comprIdnumber()','disabled' ]); !!}
		</div>
	</div>
<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Nombres: ') !!}
			{!!Form::text('name',   $solicitud->name  , ['class' => 'form-control required',  'id'=>'name']); !!}
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Apellidos: ') !!}
			{!!Form::text('lastname',  $solicitud->lastname , ['class' => 'form-control required' ,  'id'=>'lastname']); !!}
		</div>
	</div>
    <div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Telefóno Celular: ') !!}
			{!!Form::text('tel1', $solicitud->tel1, ['class' => 'form-control', 'id'=>'tel1']); !!}
		</div>
	</div>


	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Telefóno Celular 2: ') !!}
			{!!Form::text('tel2', null, ['class' => 'form-control', 'id'=>'tel2']); !!}
		</div>
	</div>

<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Dirección: ') !!}
			{!!Form::text('address', null, ['class' => 'form-control', 'id'=>'address']); !!}
	</div>
	</div>
	
    	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Orientación Sexual') !!}
			<select name="genero_id" id="genero_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($genero as $key => $tipo)
				<option value="{{$key}}">{{$tipo}}</option>
				@endforeach
			</select>
		</div>
	    </div>


    <div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Estado Civil') !!}
			<select name="estadocivil_id" id="estadocivil_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($estcivil as $key => $tipo)
				<option value="{{$key}}">{{$tipo}}</option>
				@endforeach
			</select>
		</div>
	</div>


	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Estrato ') !!}
			<select name="estrato_id" id="estrato_id" class="form-control required" required>
				<option value="">Seleccione...</option>
				@foreach($estrato as $key => $tipo)
				<option value="{{$key}}">{{$tipo}}</option>
				@endforeach
			</select>
		</div>
	</div>

	
<div class="col-md-12">
		<div class="form-group"> 

		{!!Form::label('Población Especial ') !!}
			<input type="hidden" id='pbesen' name='pbesena' value="0" checked="true">
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
  </fieldset> --}}
</div>
</div>
<hr>
<div class="row">
<div class="col-md-6 col-md-offset-3">
<button class="btn btn-primary btn-block">Registrar</button>
</div>
</div>
	{!!Form::close()!!}

         
		</div>
	</div>


	@endslot

  	@slot('footer')  
       <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>       
	@endslot
  
@endcomponent
<!-- /modal -->

