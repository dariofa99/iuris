<div class="box-body table-responsive no-padding">
<table class="table">
			<thead>
				<th>
					No Identificación
				</th>
				<th>
					Nombre
				</th>
				
				<th width="17%">
					Curso
				</th>
				
				<th>
					Acciones
				</th>
			</thead>
			<tbody>
				@foreach($estudiantes as $estudiante)
					<tr>
						<td>
							{{ $estudiante->idnumber }}
							
						</td>
						<td>
							{{ $estudiante->full_name }}
						</td>
						<td>
							{{$estudiante->cursando }}
						</td>
						<td>
							<a class="btn btn-primary btn_asig_turno"  id="estudiante_{{$estudiante->idnumber}}">Asignar Turno</a>
						</td>
						
					</tr>
				@endforeach
			</tbody>
		</table> 
		</div>
		