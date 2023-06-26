	@if( (currentUser()->hasRole('amatai') or currentUser()->hasRole('diradmin') or currentUser()->hasRole('coordprac') or currentUser()->hasRole('dirgral')) and $expediente->expestado_id!=2)
			  	<div class="row">
					<div class="col-md-12">
						<div class="box-body table-responsive no-padding">
						<table class="table">
							<thead>
								<td>
									Estudiante Asignado
								</td>
								<th>
									Nuevo estudiante
								</th>
								<th width="30%"> Acción </th>
							</thead>
							<tbody>
								<tr>
									<td>
										{{ $expediente->estudiante->name }} {{ $expediente->estudiante->lastname }}
										<input type="hidden" value="{{$periodo->id}}" id="periodo_id">
										<input type="hidden" value="{{$expediente->estudiante->idnumber}}" id="old_user_id">
									</td>
									<td>
										{!!Form::select('numberest_id',$user, null,  ['class' => 'form-control selectpicker disabled-fun4', 'data-live-search'=>'true','required' => 'required','disabled','id'=>'numberest_id' ])!!}

									</td>
									<td>
										<div>
							<a class="btn btn-warning" id="btnOpReasig" onclick="habilityButtReasCaso()"><i class="fa fa-edit"> </i>
							Reasignar</a>
							<a class="btn btn-primary" id="btnReasignar" onclick="reasigCaso()"	style="display: none;">
								<i class="fa  fa-check-circle"> </i>
							Actualizar</a>
						<a class="btn btn-danger" style="display: none;" id="btnCancReasig" onclick="hideButtReasCaso()">
								<i class="fa  fa-remove"> </i>
							Cancelar</a>
					</div>
									</td>
								</tr>
								<tr>
									<td>
										
									</td>
									<td>
										<div style="display: none;" id="cont_anotacion">
											<label>Motivo Asignación</label>
											{!!Form::select('motivo_asig_id',$motivo_asig, null, ['class' => 'form-control','required' => 'required','id'=>'motivo_asig_id' ])!!}

											<label>Anotación</label>
											<textarea name="anotacion" id="anotacion" style="width: 100%" rows="4" ></textarea>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						</div>
					</div> <!-- /.md12-->              
               </div> <!-- /.row --> 

               @endif
    @if(count($expediente->asignaciones)>1)

<div class="row">
	<hr>
	<label>Asignaciones</label>
	<div class="col-md-12">	
			<div class="box-body table-responsive no-padding">	
			<table class="table">
				<thead>
					<th>
						Fecha
					</th>
					
					<th>
						Estudiante Asignado
					</th>
					<th>
						Motivo Asignación
					</th>
					<th>
						Tipo Asignación
					</th>
					<th>
						Acción
					</th>
				</thead>
				<tbody>
					@foreach($expediente->asignaciones as $asignacion)
					<tr>
						<td>
							{{$asignacion->created_at}}
						</td>
						<td>
							{{$asignacion->estudiante->name }} {{$asignacion->estudiante->lastname }}
						</td>
						<td>
              				{{ $asignacion->motivo_asig->nom_motivo }}
            			</td>
            			
						<td>
							{{ $asignacion->tipo_asig->nombre_asig }}
						</td>
						<td>
							<a class="btn btn-success">Detalles</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>	
			</div>	

	</div>
</div>

@endif