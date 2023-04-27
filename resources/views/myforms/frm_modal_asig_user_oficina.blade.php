@component('components.modal')
	
	@slot('trigger')
		myModal_asig_user_oficina
	@endslot

	@slot('title')
		Asignando  usuario:
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')


<form method="POST"  accept-charset="UTF-8" id="myformUserStore" enctype="multipart/form-data">

{{csrf_field()}}
<input name="oficina_id" type="hidden" id="oficina_id">
<input name="id" type="hidden" id="user_id">

	{{-- <div class="col-md-12">
				<div class="form-group" align="right">			
            <input name="active" type="hidden" value="0">		
            <input checked="checked" name="active" type="checkbox" value="1">
            <label for="Usuario Activo ">Usuario Activo </label>                 
        </div> 
             </div>  --}}   



	<div class="col-md-6">
		<div class="form-group">
			<label for="Tipo de documento ">Tipo De Documento </label> 
			<select class="form-control" required="required" id="tipodoc_id" name="tipodoc_id">
            <option value="">Selecciona...</option>
            <option value="1">Sin definir</option>
            <option value="2" selected="selected">Cédula de ciudadanía</option>
            <option value="3">Cédula de extrangería</option>
            <option value="4">Pasaporte</option></select>
		</div>
	</div>



	<div class="col-md-6">
		<div class="form-group">
			<label for="Número de Identificación: ">Número De Identificación: </label>
			<input class="form-control data_user" required name="idnumber" type="text">
		</div>
	</div>



	<div class="col-md-6">
		<div class="form-group">
			<label for="Nombres: ">Nombres: </label>
			<input class="form-control" name="name" required type="text">
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="Apellidos:">Apellidos:</label>
			<input class="form-control" name="lastname" required type="text">
		</div>
	</div>




	<div class="col-md-6">
		<div class="form-group">
			<label for="Correo Principal: ">Correo Principal: </label>
			<input class="form-control" required name="email" type="text">
		</div>
	</div>

 	<div class="col-md-4">
		<div class="form-group">
			<label for="tel1">Teléfono: </label>
			<input class="form-control" name="tel1" type="text" value="0">
		</div>
	</div>

{{--
	<div class="col-md-4">
		<div class="form-group">
			<label for="Telefóno Fijo u otro: ">Telefóno Fijo U Otro: </label>
			<input class="form-control" name="tel2" type="text" value="0">
		</div>
	</div>




    <div class="col-md-4">
	    <div class="form-group">
	    	<label for="Fecha Nacimiento: ">Fecha Nacimiento: </label>
	     <div class="input-group">
	      <div class="input-group-addon">
	        <i class="fa fa-calendar"></i>
	      </div>
	      <input class="form-control" required="required" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask="" name="fechanacimien" type="text" value="1999-09-09">
	    </div>
	    <!-- /.input group -->
	    </div>
    </div> 





 	<div class="col-md-6">
		<div class="form-group">
			<label for="Estrato ">Estrato </label>
			<select class="form-control" required="required" name="estrato_id">
            <option value="">Selecciona...</option>
            <option value="9" selected="selected">Sin definir</option>
            <option value="10">1</option>
            <option value="11">2</option>
            <option value="12">3</option>
            <option value="13">4</option>
            <option value="14">5</option>
            <option value="15">6</option></select>
		</div>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<label for="Orientación Sexual">Orientación Sexual</label>
			<select class="form-control" required="required" name="genero_id">
            <option value="">Selecciona...</option>
            <option value="6" selected="selected">Masculino</option>
            <option value="7">Femenino</option>
            <option value="8">LGTBI</option>
            </select>
		</div> 
	</div>


	
    <div class="col-md-6">
		<div class="form-group">
			<label for="Estado Civil">Estado Civil</label>
			<select class="form-control" required="required" name="estadocivil_id">
            @foreach ($roles as $key => $rol )
            <option value="{{$key}}">{{$rol}}</option>
        @endforeach
             
             </select>
		</div>
	</div>--}}

    <div class="col-md-6">
		<div class="form-group">
			<label for="idrol">Rol</label> <span id="lbl_role_name"> </span>
			<select name="idrol" id="idrol" class="form-control">
        <option value="">Seleccione...</option>
        @foreach ($roles_profext as $key => $rol )
            <option value="{{$key}}">{{$rol}}</option>
        @endforeach

        </select>
        	</div>
	</div>

    <div class="col-md-6">
		<div class="form-group">
			<br>
			<button type="submit" class="btn btn-block btn-primary btn-sm">
            Guardar y asignar
            </button>
            
            	</div>
	</div>





	
	
<!-- 	<div class="col-md-4">
		<div class="form-group">
			<label for="Acceso a Oficina Virtual ">Acceso A Oficina Virtual </label>
			<select class="form-control" required="required" name="accesofvir"><option value="">Selecciona...</option><option value="0" selected="selected">No</option><option value="1">Si</option></select>
		</div>
	</div>


		<div class="col-md-4">
		<div class="form-group">
			<label for="Institucion ">Institucion </label>
			<select class="form-control" required="required" name="institution"><option value="">Selecciona...</option><option value="1">Universidad de Nariño</option><option value="2">Universidad Mariana</option><option value="3">Corporación Universitaria Cesmag</option></select>
		</div>
	</div> 




	<div class="col-md-4">
		<div class="form-group">
			<label for="Dirección: ">Dirección: </label>
			<input class="form-control" name="address" type="text" value="12">
		</div>
	</div>




	<div class="col-md-4">
		<div class="form-group">
			<label for="Descripción: ">Descripción: </label>
			<input class="form-control" name="description" type="text" value="12">
		</div>
	</div>
	

	<div class="col-md-4">
    <div class="form-group">
        <label for="image">Imagen de perfil</label>
        <input name="image" type="file" id="image">
    </div>
    </div>
	<div class="col-md-12" align="right">
		<div class="form-group">
			<br>
						<button class="btn btn-primary">Enviar</button>			
					</div>
	</div>-->

</form>

	@endslot
@endcomponent
<!-- /modal -->










