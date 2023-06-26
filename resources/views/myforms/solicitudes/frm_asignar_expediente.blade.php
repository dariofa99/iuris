@if(count($solicitud->expedientes)<=0)
<form id="myFormAsigExpe">
{!!Form::hidden('expestado_id', 1, ['class' => 'form-control'] ); !!}
 @if($periodo)
{!!Form::hidden('periodo_id', $periodo->id, ['class' => 'form-control'] ); !!}
@endif
<div class="row">
<div class="col-md-4">
				      	<div class="form-group">	
							<label for="Identidicación Solicitante: ">Identidicación Solicitante: </label>
				 			              
                			<!-- /btn-group -->
                			<input class="form-control required" name="expidnumber" required="required" value="{{$solicitud->idnumber}}" readonly="" id="expidnumber" type="text">
              			</div>
</div> <!-- /.md4-->  
<div class="col-md-4">
	<div class="form-group">
    <label>Rama del derecho</label>
   <select name="expramaderecho_id" id="expramaderecho_id" class="form-control" required="">
				<option value="">Selecciona...</option>
				@foreach($rama_derecho as $key=> $value)				
				<option value="{{$key}}">{{$value}}</option>			
				@endforeach
		</select>
    </div>
</div>

<div class="col-md-4">
	<div class="form-group">
    <label>Tipo de procedimiento</label>
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

<div class="col-md-3">
	<div class="form-group">
    <br>
    <button class="btn btn-primary btn-sm btn-block" 
	@if($solicitud->type_status_id == 157  || $solicitud->type_status_id == 158 || $solicitud->type_status_id == 155 || $solicitud->type_status_id == 171 || ($solicitud->type_status_id == 156 and !$solicitud->user)) disabled data-toggle="tooltip" 
		title="Para asignar el expediente debes aceptar la solicitud{{!$solicitud->user ? " y esperar el registro del usuario." :"."}}" @endif>
		Asignar expediente
	</button>
    </div>
</div>
</div>
</form>
@else
<div class="row">
<div class="col-md-12">
	<h3>Ya tiene un expediente asignado 
	<a target="_blank" href="{{url("/expedientes/".$solicitud->expedientes[0]->expid."/edit")}}">(Ver)</a> </h3>
</div>
</div>

@endif