@extends('layouts.dashboard')


@section('titulo_general')
Asignaciones
@endsection

@section('titulo_area')
Estudiantes Asignados
@endsection


@section('area_forms') 
<div class="box-body table-responsive no-padding">
<table class="table">
	<thead>
		<th>
			Cédula
		</th>
		<th width="30%">
			Estudiante
		</th>
@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
		<th>
			Docente Asignado
		</th>
@endif
		<th>
			Curso
		</th>
		<th>
			No Expedientes
		</th>
		<th>
			Promedio Notas
		</th>
		<th width="24%">
			Acciones
		</th>
	</thead>

	<tbody>
		@foreach($asignaciones as $asignacion)
		
		<tr>
	 		<td>
				{{ $asignacion->estudiante->idnumber }}
			</td>
			<td>
				{{ $asignacion->estudiante->name }} 
				{{ $asignacion->estudiante->lastname }} 
			</td>
@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
			<td>
				<div id="select_docentes_div-{{ $asignacion->id }}" style="display: none;">
					<select class="form-control selectpicker" data-live-search="true" id="select_docentes-{{ $asignacion->id }}" name="expidnumberest" >
  				 @foreach($docentes as $key => $user)
					 
							<option @if($user->idnumber==$asignacion->docente->idnumber) selected @endif value="{{$user->idnumber}}">{{$user->full_name}}</option>
					
						@endforeach
				</select>
				</div>
			<label id="lbl_nombres_docente-{{ $asignacion->id }}">
				{{ $asignacion->docente->name }} 
				{{ $asignacion->docente->lastname }} 
			</label>	
				
				
			</td>
@endif
			<td>
				{{ $asignacion->estudiante->curso->ref_nombre }}
			</td>
			<td>
				{{ number_format(count($asignacion->estudiante->expedientes)) }}
			</td>
			<td>
				-null-
			</td>
			<td>
			<div id="btns_edit-{{$asignacion->id}}">
				{{ link_to_route('expedientes.index','Expedientes',['tipo_busqueda'=>'estudiante','data'=>$asignacion->estudiante->idnumber],['class'=>'btn btn-success']) }}
@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
			<button class="btn btn-primary btn_cambiar_docente" id="btn_cambiar_docente-{{ $asignacion->id }}">
				Editar Docente
			</button>
@endif
			</div>	
@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
			<div id="btns_act-{{$asignacion->id}}" style="display: none;">
			<button class="btn btn-warning btn_actualizar_docente" id="btn_actualizar_docente-{{ $asignacion->id }}">
				Actualizar
			</button>

			<button class="btn btn-danger btn_cancel_edit" id="btn_cancel_edit-{{ $asignacion->id }}">
				<i class="fa fa-close"></i>
			</button>
			</div>
@endif
			


			</td>
		</tr>			
	
		@endforeach
	</tbody>
</table>
</div>
{{ $asignaciones->render() }}
@stop
