@if($fecha =$expediente->fechaVigente($expediente->expfecha_res))
<div class="callout callout-info">
        <h3>¡Respuesta en proceso!</h3>
        <p style="font-size:18px;">La fecha límite establecida para tener una respuesta preliminar es el día <b>{{ $fecha }}</b>; le recomendamos estar pendiente después de la fecha señalada; debe tener me cuenta que la fecha puede cambiar según la congestión de solicitudes.</p>
       
    </div>

@else

    @if($expediente->expestado_id == '1' or $expediente->expestado_id == '3' or $expediente->expestado_id == '4') 
        @if($expediente->exphechos != "" && $expediente->exprtaest !="")
    <div class="callout callout-warning">
          <h4>¡Respuesta preliminar!</h4>

          <p style="font-size:16px;">La respuesta expuesta a continuación es preliminar, no cuenta con la revisión pertinente por parte del docente asesor, la respuesta puede variar hasta que el docente realice las revisiones necesarias; si usted considera que los hechos no corresponden a la realidad de su caso, puede notificarnos por medio del botón interponer queja.</p>
    </div>
        @endif
    @elseif($expediente->expestado_id == '2')
    <div class="callout callout-success">
          <h4>¡Respuesta revisada!</h4>
          <p style="font-size:16px;">La respuesta ha sido revisada por el docente asesor y se encuentra validada según los hechos del caso; si tiene dudas de la respuesta o necesita aclaracione sobre su caso, puede comunicarce con el estudiante. </p>
    </div>
    @else
    <div class="callout callout-danger">
          <h4>¡Respuesta con problemas!</h4>
          <p style="font-size:16px;">Tu consulta no ha tenido el tramite requerido, puedes interponer una queja para detarminar que a susedido con el estudiante asesor.</p>
    </div>
    @endif
    @if($expediente->exphechos == "" or $expediente->exprtaest =="")
    <div class="callout callout-danger" style="font-size:16px;">
          <h4>¡Respuesta con problemas!</h4>
          <p>Al parecer tu consulta presenta problemas, puedes interponer una queja para determinar que ha pasado.</p>
    </div>
    @endif
        
@endif

    <!--cont_data_req-->
    <div >

    @if($fecha == false && ($expediente->exphechos != "" && $expediente->exprtaest !=""))

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="Hechos caso: ">Hechos Caso: </label>
                    <textarea class="form-control" maxlength="100000" id="exp_hechos" disabled="" name="exphechos" cols="50" rows="20">{{$expediente->exphechos}}</textarea>
                </div>	
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
			    <div class="form-group">
                    <label for="Hechos caso: ">Respuesta Estudiante: </label>
                    <textarea class="form-control" maxlength="100000" id="exp_hechos" disabled="" name="exphechos" cols="50" rows="20">{{$expediente->exprtaest}}</textarea>
                </div>	
			</div>
        </div>
        @endif
        <div class="row">
			<div class="col-md-12" align="right">
		    	<div class="form-group" >
                  <br/>
                  
                  <a href="#tab_9" data-toggle="tab" class="btn btn-primary btn-lg" >Interponer queja</a>
				</div>
            </div>
        </div>
        
    </div>
<!--cont_data_req-->


