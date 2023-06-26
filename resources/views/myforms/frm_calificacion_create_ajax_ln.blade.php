@foreach($expediente->get_notas_caso() as $nota)
					<tr>
						<td>
							{{($nota['segmento'])}}
						</td>
						<td>
							@if(count($nota['nota_conocimiento'])>0 and $nota['nota_conocimiento']['id']!=0)
								{{($nota['nota_conocimiento']['nota'])}}
								@else
								Sin evaluar
							@endif
						</td> 
						<td>
							@if(count($nota['nota_aplicacion'])>0 and $nota['nota_aplicacion']['id']!=0)
								{{($nota['nota_aplicacion']['nota'])}}
								@else
							Sin evaluar
							@endif
						</td>
						<td>
							@if(count($nota['nota_etica'])>0 and $nota['nota_etica']['id']!=0)
								{{($nota['nota_etica']['nota'])}}
								@else
							Sin evaluar
							@endif
						</td>	
					
						<td>
							@if(count($nota['nota_etica'])>0 and $nota['nota_etica']['id']!=0)
								{{($nota['nota_etica']['tipo'])}} 
								@else
								Sin evaluar
							@endif
						</td>
						<td>
							
							@if(count($nota['nota_concepto'])>0 and $nota['nota_concepto']['id']!=0)
							<button class="btn btn-success btn_detalles_nota" id="{{$nota['nota_concepto']['id']}}">
								Detalles
							</button>

							<input hidden id="conceptoNota_{{$nota['nota_concepto']['id']}}" value="{{$nota['nota_concepto']['nota']}}">
							<input hidden id="docenteEva_{{$nota['nota_concepto']['id']}}" value="{{$nota['nota_concepto']['docevname']}}">								
							@endif
						</td>
					</tr>
				@endforeach