@component('components.modal')
	
	@slot('trigger')
		myModal_solicitud_acept
	@endslot

	@slot('title')
		Aceptando solicitud
	@endslot

 
	@slot('body')



@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div id="contcreateof">


<form method="POST"  accept-charset="UTF-8" id="myformAceptSolicitud">

{{csrf_field()}}

<input name="solicitud_id" type="hidden">



	{{-- <div class="col-md-8 col-md-offset-2">
	<div class="form-group">
    <label for="hte">
    <input type="checkbox" name="hte" id="hte" value="1"> Habilitar tiempo de espera</label>
       
    </div>
	</div>

    <div class="col-md-8 col-md-offset-2" id="cont_ites" style="display:none">
	<div class="form-group">
    <label for="tiempo_espera_"> Tiempo en minutos
                </label>
        <input id="tiempo_espera_" name="tiempo_espera" type="text" required class="form-control" disabled>
    </div>
	</div> --}}


	<div class="col-md-8 col-md-offset-2">
		<div class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			 <strong>Atención!</strong>
			¿Está seguro de aceptar la solicitud...?
		  </div>
	</div>
	
	  

    <div class="col-md-8 col-md-offset-2">
		<div class="form-group">
			<br>
			<button type="submit" class="btn btn-block btn-primary btn-sm">
            Aceptar solicitud
            </button>
            
            	</div>
	</div>



</form>
</div>
	@endslot
@endcomponent
<!-- /modal -->










