	@foreach($oficinas as $oficina)
					<tr>	
						<td>{{$oficina->nombre}}	</td>	
						<td>{{$oficina->ubicacion}}	</td>	

						<td>
						<button class="btn btn-primary btn-sm btn_edit_oficina" data-id="{{$oficina->oficina_id}}" id="btn_edit_oficina-{{$oficina->oficina_id}}">Editar</button>
						<button class="btn btn-danger btn-sm btn_delete_oficina" data-id="{{$oficina->oficina_id}}" id="btn_asig_usuarios-{{$oficina->oficina_id}}">Eliminar</button>
						<button class="btn btn-warning btn-sm btn_asig_usuarios" data-id="{{$oficina->oficina_id}}" id="btn_asig_usuarios-{{$oficina->oficina_id}}">
						Asignar usuarios</button>
						<button class="btn btn-default btn-sm btn_ver_usuarios" data-id="{{$oficina->oficina_id}}" 
						id="btn_ver_usuarios-{{$oficina->oficina_id}}">
						Ver usuarios</button>
						
							</td>					
						
					</tr>
					@endforeach
{{-- 
{!! $periodos->appends(request()->query())->links()!!} --}}

					