<div class="box-body table-responsive no-padding">
<table class="table" >	
				<thead>	
					<th>Nombre</th>
					<th>Fecha de Inicio	</th>
					<th>Fecha final</th>
					<th width="10%">Estado</th>
					<th></th>
					<th>Acciones</th>
				</thead>
				<tbody>	
					@foreach($periodos as $segmento)
					<tr>	
						<td>{{  $segmento->prddes_periodo }}</td>
						<td>{{ $segmento->prdfecha_inicio }}</td>
						<td>{{ $segmento->prdfecha_fin }}</td>
						<td>
							@if($segmento->estado)
							<label class="bg-green dis-block">{{ $segmento->getEstado() }}</label>
							@else 
							<label class="bg-red dis-block">{{ $segmento->getEstado() }}</label>
							@endif
							

						</td>
						<td>
							
							<input type="radio" @if($segmento->estado) checked="true"  @endif name="radio_state_periodo" id="radio_state_periodo-{{ $segmento->periodo_id }}" class="radio_state_periodo">
							
							
							
						</td>
						<td>							
							<button type="button" class="btn btn-primary btn_edit_per" id="btn_editar-{{ $segmento->periodo_id }}">
Editar
							</button>

							<button type="button" class="btn btn-danger btn_del_per" id="btn_delete-{{ $segmento->periodo_id }}">
Eliminar
							</button>

							</td>
						
					</tr>

					@endforeach
						

				</tbody>

						

			</table>
			</div>
{{-- 
{!! $periodos->appends(request()->query())->links()!!} --}}

					