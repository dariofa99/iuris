
<table class="table" id="content_list_solicitudesh_table">	
				<thead>	
                <th>Turno</th>
					<th>Número</th>
					<th>Nombres</th>					
					<th>Estado</th>
					<th>Creado</th>
					<th>Acciones</th>
				</thead>
				<tbody>	
               @foreach ($solicitudesh as $solicitud)
				   <tr @if($solicitud->type_status_id==154) style="background:#F8F9F9" @endif>
				   <td>
				   {{$solicitud->turno}}
				   </td>
                   <td>
				   {{$solicitud->number}}
				   </td>
				   <td>
				   {{$solicitud->name}} {{$solicitud->lastname}}
				   </td>				  
				   <td>
				   {{$solicitud->estado->ref_nombre}}
				   </td>
				   <td>
				     {{  \Carbon\Carbon::parse($solicitud->created_at)->diffForHumans() }}
				   </td>

				   <td>
					@if(auth()->user() and auth()->user()->can('admin_solicitudes'))  
				   	<a target="_blank" href="{{route('solicitudes.edit',$solicitud->solicitud_id)}}" class="btn btn-primary"> <i class="fa fa-eye"> </i> Revisar</a>
					@endif   
				</td>
				   
				   </tr>
			   @endforeach
				</tbody>

						

			</table>

			{{ $solicitudesh->appends(request()->query())->links() }}