@component('components.modal')
	
	@slot('trigger')
		myModal_exp_user_details
	@endslot

	@slot('title')
		Detalles Usuario
	@endslot


	@slot('body')




	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden" id="usercreated" name="usercreated" value="{{currentUser()->idnumber}}" >
	<input type="hidden" id="userupdated" name="userupdated" value="{{currentUser()->idnumber}}" >


	{!!Form::hidden('id',  null , ['class' => 'form-control', 'readonly', 'id'=>'id' ]); !!}



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')


					
<div class="row">
	<div class="col-md-12">
		<label for="active">Activo:</label><br>
		<input type="hidden" name="active" id="active_text" value="0">
		<input type="checkbox" disabled name="active" id="active_us" value="1">
	</div>
</div>




	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Identificación: ') !!}
			{!!Form::text('idnumber',  null  , ['class' => 'form-control', 'disabled', 'id'=>'idnumber' ]); !!}
		</div>
	</div>

			


	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Nombres: ') !!}
			{!!Form::text('name',   null  , ['class' => 'form-control required', 'disabled',  'id'=>'name_us']); !!}
		</div>
	</div>




	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Apellidos: ') !!}
			{!!Form::text('lastname',  null , ['class' => 'form-control required', 'disabled' ,  'id'=>'lastname_us']); !!}
		</div>
	</div>





<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Correo Principal: ') !!}
			{!!Form::text('email', null, ['class' => 'form-control required', 'disabled', 'id'=>'email']); !!}
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Tel. Celular: ') !!}
			{!!Form::text('tel1', null, ['class' => 'form-control', 'disabled', 'id'=>'tel1']); !!}
		</div>
	</div>


	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Telefóno Fijo: ') !!}
			{!!Form::text('tel2', null, ['class' => 'form-control', 'disabled', 'id'=>'tel2']); !!}
		</div>
	</div>



	<div class="col-md-4">
		<div class="form-group">
			{!!Form::label('Orientación Sexual') !!} 

			<select name="genero_id" id="genero_id" class="form-control required" disabled>
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
			<select name="estadocivil_id" id="estadocivil_id" class="form-control required" disabled>
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
			<select name="estrato_id" id="estrato_id" class="form-control required" disabled>
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
			<input type="hidden" id='pbesen' name='pbesena' value="0" disabled checked="true">
		    <div class="checkbox">
                    <label>
                      {!!Form::checkbox('pbesena', '1', false, ['id'=>'pbesena', 'disabled','class'=>'check-use']);!!} 
                       Sisben
                    </label>
            </div> 



		    <div class="checkbox">
		    	<input type="hidden" id='pbepersondisca' name='pbepersondiscap' value="0" checked="true">
                    <label>
                      {!!Form::checkbox('pbepersondiscap', '1', false, ['id'=>'pbepersondiscap', 'disabled','class'=>'check-use']);!!} 
                       Persona Discapacitada
                    </label>
            </div>

           
            <div class="checkbox">
            	<input type="hidden" id='pbepersondisca' name='pbevictimconflic' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbevictimconflic', '1', false, ['id'=>'pbevictimconflic', 'disabled','class'=>'check-use']);!!} 
                       Víctima del desplazamiento y conflicto
                    </label>
            </div>


            
            <div class="checkbox">
            	<input type="hidden" id='pbepersondisca' name='pbeadultomayor' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbeadultomayor', '1', false, ['id'=>'pbeadultomayor', 'disabled','class'=>'check-use']);!!} 
                       Adulto Mayor
                    </label>
            </div>

            		   

            <div class="checkbox">
            	<input type="hidden" id='pbepersondisca' name='pbeminoetnica' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbeminoetnica', '1', false, ['id'=>'pbeminoetnica', 'disabled'=>'disabled','class'=>'check-use']);!!} 
                       Minoria Étnica
                    </label>
            </div>

            


            <div class="checkbox">
            	<input type="hidden" id='pbepersondisca' name='pbemadrecomuni' value="0" checked="true">
                
                    <label>
                      {!!Form::checkbox('pbemadrecomuni', '1', false, ['id'=>'pbemadrecomuni', 'disabled'=>'disabled','class'=>'check-use']);!!} 
                       Madre Comunitaria
                    </label>
            </div>

            


            <div class="checkbox">
            	<input type="hidden" id='pbepersondisca' name='pbecabezaflia' value="0" checked="true">                
                    <label>
                      {!!Form::checkbox('pbecabezaflia', '1', false, ['id'=>'pbecabezaflia', 'disabled'=>'disabled','class'=>'check-use']);!!} 
                      Madre o padre cabeza de familia
                    </label>
            </div>

            


            <div class="checkbox">
            	<input type="hidden" id='pbepersondisca' name='pbeninguna' value="0" checked="true">                
                    <label>
                      {!!Form::checkbox('pbeninguna', '1', false, ['id'=>'pbeninguna', 'disabled'=>'disabled','class'=>'check-user-dis']);!!} 
                       Ninguna de las anteriores
                    </label>
            </div>


		</div>
	</div>


	<div id="content_aditional_data">
	
	</div>
	

	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Dirección: ') !!}
			{!!Form::text('address', null, ['class' => 'form-control', 'disabled', 'id'=>'address']); !!}
		</div>
	</div>




	<div class="col-md-6">
		<div class="form-group">
			{!!Form::label('Descripción: ') !!}
			{!!Form::text('description', null, ['class' => 'form-control', 'disabled', 'id'=>'description']); !!}
		</div>
	</div>





	







	@endslot
@endcomponent
<!-- /modal -->










