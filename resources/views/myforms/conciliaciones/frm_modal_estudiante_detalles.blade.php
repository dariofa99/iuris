@component('components.modal')
	
	@slot('trigger')
		myModal_estudiante_detalles
	@endslot

	@slot('title')
		Datos generales del estudiante:
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection

<div class="row" id="myformDetallesEstudiante">
    <div class="col-md-4">
        <img src="{{ 'thumbnails/'}}" id="user_image" style="border-radius: 10px;-webkit-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);-moz-box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75);box-shadow: -9px 10px 9px 0px rgba(0,0,0,0.75); width: 180px;"  alt="User Image">
    </div>
    <div class="col-md-7" style="margin-left: 4px">
        <p>IDENTIFICACIÓN: <label id="lbl_user_idnumber"></label> </p>
        <p>NOMBRES: <label id="lbl_user_name"></label> </p>
        <p>CORREO: <label id="lbl_user_email"></label> </p>
        <p>TELÉFONO: <label id="lbl_user_tels"></label> </p>
        <p>DIRECCIÓN: <label id="lbl_user_direccion"></label> </p>
    </div>
</div>



	@endslot
@endcomponent
<!-- /modal -->










