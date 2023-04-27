@extends('layouts.dashboard')
@section('titulo_area')
Solicitudes
@endsection

@section('area_forms')

@include('msg.success')
<div class="row">




</div>
<div class="row">
<div class="col-md-12 table-responsive no-padding" id="content_list_oficinas">

<table class="table" id="content_list_solicitudes_table">	
				<thead>	
					<th>Número</th>
					<th>Nombres</th>					
					<th>Estado</th>
					<th>Creado</th>
					<th>Acciones</th>
				</thead>
				<tbody>	
               @foreach ($solicitudes as $solicitud)
				   <tr>
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
				   <a target="_blank" href="{{route('solicitudes.edit',$solicitud->id)}}" class="btn btn-primary"> <i class="fa fa-eye"> </i> Revisar</a>
				   </td>
				   
				   </tr>
			   @endforeach
				</tbody>

						

			</table>
			

</div>
</div>



              @stop