@extends('layouts.dashboard')


@section('titulo_general')
Segmentos
@endsection

@section('titulo_area')
Listar Segmentos
@endsection


@section('area_forms')

@include('msg.success')

<div class="row">	

		<div class="col-md-12">	 
		<div class="box-body table-responsive no-padding">
			<table class="table">	
				<thead>
					<th>Usuario</th>	
					<th>Expediente</th>	
					<th>Host</th>					
					<th>Acciones</th>
				</thead>
				<tbody>	
					@foreach($auditorias as $auditoria)
						<tr>
							<td>
								@if($auditoria->usuario)
								{{$auditoria->usuario->name}} {{$auditoria->usuario->lastname}}
								@endif
							</td>
							<td>
								@if($auditoria->expediente)
								{{$auditoria->expediente->expid}}
								@endif
							</td>
							<td>
								{{$auditoria->host}}
							</td>
							<td>
								<a  onclick="getDetailsAuditoria({{$auditoria->id}})" class="btn btn-success">Detalles</a>
							</td>
						</tr>
					@endforeach 
						

				</tbody>

						

			</table>
			</div>
{!! $auditorias->appends(request()->query())->links()!!}  


		</div>

</div>

@include('myforms.frm_auditoria_details')
@stop
