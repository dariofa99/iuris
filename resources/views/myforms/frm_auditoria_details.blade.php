@component('components.modal_dynamic')
	
	@slot('trigger')
		myModal_auditoria_details
	@endslot

	@slot('title')
		<h5><label>Detalles Auditoria</label></h5>	
	@endslot

	@slot('size')
		modal-dialo
	@endslot

	@slot('num_cols_bootstrap')
		col-md-12	
	@endslot

 
	@slot('body')

{!!Form::open([ 'id'=>'myform_act_edit_doc','files' => true])!!}
	<input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
	<input type="hidden"  id="idact2">

 
@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
 <div class="row">

 	<div class="row">
		<div class="col-md-12">
		<div class="box-body table-responsive no-padding">
			<table class="table">
					<tr>
						<td>
							Usuario
						</td>
						<td>
							<label id="label_userAuditoria"></label>
						</td>
						<td>
							Fecha de Evento
						</td>
						<td>
							<label id="label_fechaAuditoria"></label>
						</td>
					</tr>
					
				</table>
				</div>			
		</div>
	</div>
 	

			<div class="col-md-6">
				<div class="box-body table-responsive no-padding">
				<table class="table">
					<tr>
						<td colspan="2">
							<h3>Datos del expediente Auditado</h3>
						</td>
					</tr>
					
					<tr>
						<td width="40%">
							No Expediente:
						</td>
						<td>
							<label id="label_expidAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Fecha:
						</td>
						<td>
							<label id="label_expfechaAuditoria"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Rama derecho:
						</td>
						<td>
							<label id="label_expramaderecho_idAuditoria"></label>
						</td>
					</tr>
					

					<tr>
						<td width="40%">
							Id Estado:
						</td>
						<td>
							<label id="label_expestado_idAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Consultante
						</td>
						<td>
							<label id="label_expidnumberAuditoria"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Tipo Proceso
						</td>
						<td>
							<label id="label_exptipoproce_idAuditoria"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Tipo Caso:
						</td>
						<td>
							<label id="label_exptipocaso_idAuditoria"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Estudiante:
						</td>
						<td>
							<label id="label_expidnumberestAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Departamento:
						</td>
						<td>
							<label id="label_expdepto_idAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Municipio:
						</td>
						<td>
							<label id="label_expmunicipio_idAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Tipo Vivienda:
						</td>
						<td>
							<label id="label_exptipovivien_idAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Personas a cargo:
						</td>
						<td>
							<label id="label_expperacargoAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Ingresos Mensuales:
						</td>
						<td>
							<label id="label_expingremensualAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Egresos Mensuales:
						</td>
						<td>
							<label id="label_expegremensualAuditoria"></label>
						</td>
					</tr>

					

					<tr>
						<td width="40%">
							Hechos
						</td>
						<td>
							<label id="label_exphechosAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td>
							Respuesta del Estudiante
						</td>
						<td>
							<label id="label_exprtaestAuditoria"></label>
						</td>													
					</tr>
					

					<tr>
						<td width="40%">
							Juzgado o Entidad:
						</td>
						<td>
							<label id="label_expjuzoentAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							No Proceso:
						</td>
						<td>
							<label id="label_expnumprocAuditoria"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Persona Demandante:
						</td>
						<td>
							<label id="label_exppersondemandanteAuditoria"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Persona Demandada:
						</td>
						<td>
							<label id="label_exppersondemandadaAuditoria"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Fecha Limite:
						</td>
						<td>
							<label id="label_expfechalimiteAuditoria"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Usuario que Actualizo:
						</td>
						<td>
							<label id="label_expuserupdatedAuditoria"></label>
						</td>
					</tr>



	
					



					
						
				
				</table>
				</div>
		    </div>

		    <div class="col-md-6">
			<div class="box-body table-responsive no-padding">				
				<table class="table">
					<tr>
						<td colspan="2">
							<h3>Datos del expediente Actual</h3>
						</td>
					</tr>

					<tr>
						<td width="40%">
							No Expediente:
						</td>
						<td>
							<label id="label_expidActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Fecha:
						</td>
						<td>
							<label id="label_expfechaActual"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Rama derecho:
						</td>
						<td>
							<label id="label_expramaderecho_idActual"></label>
						</td>
					</tr>
					

					<tr>
						<td width="40%">
							Id Estado:
						</td>
						<td>
							<label id="label_expestado_idActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Consultante
						</td>
						<td>
							<label id="label_expidnumberActual"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Tipo Proceso
						</td>
						<td>
							<label id="label_exptipoproce_idActual"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Tipo Caso:
						</td>
						<td>
							<label id="label_exptipocaso_idActual"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Estudiante:
						</td>
						<td>
							<label id="label_expidnumberestActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Departamento:
						</td>
						<td>
							<label id="label_expdepto_idActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Municipio:
						</td>
						<td>
							<label id="label_expmunicipio_idActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Id Tipo Vivienda:
						</td>
						<td>
							<label id="label_exptipovivien_idActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Personas a cargo:
						</td>
						<td>
							<label id="label_expperacargoActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Ingresos Mensuales:
						</td>
						<td>
							<label id="label_expingremensualActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Egresos Mensuales:
						</td>
						<td>
							<label id="label_expegremensualActual"></label>
						</td>
					</tr>

					

					<tr>
						<td width="40%">
							Hechos
						</td>
						<td>
							<label id="label_exphechosActual"></label>
						</td>
					</tr>

					<tr>
						<td>
							Respuesta del Estudiante
						</td>
						<td>
							<label id="label_exprtaestActual"></label>
						</td>													
					</tr>
					

					<tr>
						<td width="40%">
							Juzgado o Entidad:
						</td>
						<td>
							<label id="label_expjuzoentActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							No Proceso:
						</td>
						<td>
							<label id="label_expnumprocActual"></label>
						</td>
					</tr>

					<tr>
						<td width="40%">
							Persona Demandante:
						</td>
						<td>
							<label id="label_exppersondemandanteActual"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Persona Demandada:
						</td>
						<td>
							<label id="label_exppersondemandadaActual"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Fecha Limite:
						</td>
						<td>
							<label id="label_expfechalimiteActual"></label>
						</td>
					</tr>
					<tr>
						<td width="40%">
							Id Usuario que Actualizo:
						</td>
						<td>
							<label id="label_expuserupdatedActual"></label>
						</td>
					</tr>
					
						
				
				</table>
				</div>
		    </div>

 </div>
	<div class="col-md-2">
		<div class="form-group">
			<br>
			
		</div>
	</div>

	

	{!!Form::close()!!}


	@endslot
@endcomponent
<!-- /modal -->










