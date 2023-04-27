	@foreach($oficina->turnos as $turno)
					<tr>	
						<td>{{$turno->estudiante->name}} {{$turno->estudiante->lastname}}	</td>	
						<td>{{$turno->estudiante->curso->ref_nombre}}	</td>	
                        <td>{{$turno->estudiante->email}}	</td>	
                        <td>{{$turno->estudiante->tel1}} - {{$turno->estudiante->tel2}}	</td>	
<td>{{$turno->dia->ref_nombre}} </td>	

						<td>
				
			
						<button class="btn btn-warning btn-sm btn_asig_notas_ext" data-id="{{$turno->estudiante->idnumber}}" id="btn_asig_usuarios-{{$turno->estudiante->id}}">
						Notas</button>
			
						<button class="btn btn-default btn-sm btn_ver_usuarios" data-id="{{$turno->estudiante->idnumber}}" 
						id="btn_ver_usuarios-{{$turno->estudiante->id}}">
						Ver</button>
						
							</td>					
						
					</tr>
					@endforeach
{{-- 
{!! $periodos->appends(request()->query())->links()!!} --}}

					