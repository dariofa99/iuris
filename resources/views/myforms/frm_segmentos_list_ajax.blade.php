<div class="box-body table-responsive no-padding">
<table class="table">	
				<thead>	
					<th>Nombre</th>	
					<th>Fecha de Inicio	</th>
					<th>Fecha final</th>
					<th>Periodo</th>
					<th width="10%">Estado</th>
					<th></th>
					
					<th>Acciones</th>
				</thead>
				<tbody>	
					@foreach($segmentos as $segmento)
					<tr>	
						<td>{{ $segmento->segnombre }}</td>
						<td>{{ $segmento->fecha_inicio }}</td>
						<td>{{ $segmento->fecha_fin }}</td>
						<td>
							{{ $segmento->prddes_periodo }}
						</td>
						<td>
							@if($segmento->estado)
							<label class="bg-green dis-block">{{ $segmento->getEstado() }}</label>
							@else
							<label class="bg-red dis-block">{{ $segmento->getEstado() }}</label>
							@endif
							

						</td>
						<td>
							<input type="radio" @if($segmento->estado) checked="true"  @endif name="radio_state_segmen" id="radio_state_segmento-{{ $segmento->id }}" class="radio_state_segmento" value="{{$segmento->act_fc}}">
							
						</td>
						 
						<td>

						<button type="button" id="segmento_id-{{$segmento->id}}" class="btn btn-primary btn_edit_seg">
							Editar
						</button>

						<button type="button" id="segmento_id-{{$segmento->id}}" class="btn btn-danger btn_del_seg">
							Eliminar
						</button>
						

						@if($segmento->est_evaluado)
						<a href=#" disabled id="segmento_id-{{$segmento->id}}" class="btn btn-warning ">
							Cerrado
						</a>
						@else
						@if(date('Y-m-d')>=$segmento->fecha_fin )

						<a href="#" id="segmento_id-{{$segmento->id}}" class="btn btn-warning btn_cerrar_seg" >
							Cerrar Corte
						</a> 

						@else

						<a href=#" disabled id="segmento_id-{{$segmento->id}}" class="btn btn-warning ">
							Fecha
						</a>

						@endif
						@endif		

						

							</td>
						
					</tr>

					@endforeach
						

				</tbody>

						

			</table>
			</div>