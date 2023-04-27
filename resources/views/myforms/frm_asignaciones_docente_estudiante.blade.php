@extends('layouts.dashboard')


@section('titulo_general')
Asignaciones Docente
@endsection

@section('titulo_area')
Docentes Activos
@endsection
 

@section('area_forms')

@include('msg.success') 

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" class="tab-btn-show-notas" data-toggle="tab">Docentes Asignados</a></li>
             
              <li><a href="#tab_2" class="tab-btn-show-notas" data-toggle="tab">Docentes por Asignar</a></li>
				
			<li><a href="#tab_3" class="tab-btn-show-notas" data-toggle="tab">Estudiantes por Asignar</a></li>
	

               
              
            </ul>
            <div class="tab-content">
			
			<!--Tab panel 1-->
              <div class="tab-pane active" id="tab_1">
	<div class="row">
	<div class="col-md-12 border-g">
	<div class="box-body table-responsive no-padding">
		<table class="table" id="table_list_docentes_horas">
			<thead>
				<th>
					No Identificación
				</th>
				<th>
					Nombre
				</th>
				<th >
					Horas J. Mañana
				</th>
				<th>
					Horas J. Tarde
				</th>
				<th>
					No Max. Estudiantes
				</th>
				<th>
					No Est. Asignados
				</th>
				<th width="20%">
					Acciones
				</th>
			</thead>
			<tbody>
				@foreach($horarios_docente as $horario) 
				
					<tr>
						<td>
							{{ $horario->docente->idnumber }}
							<input value="{{$horario->docente->idnumber}}" type="hidden" class="form-control" name="docidnumber[]" required>
						</td>
						<td>
							{{ $horario->docente->name }} {{ $horario->docente->lastname }}
						</td>
						<td>
							<label id="label_horas_a{{$horario->id}}">{{$horario->horas_a }}</label>
							<input type="hidden" id="horas_a{{$horario->id}}" class="form-control required" name="horas_a" value="{{$horario->horas_a }}">

						</td>
						<td>
							
							<label id="label_horas_b{{$horario->id}}">{{$horario->horas_b }}</label>

							<input type="hidden" id="horas_b{{$horario->id}}" class="form-control required" name="horas_b" value="{{$horario->horas_b }}">
						</td>
						<td>
							
							<label id="label_num_max_est{{$horario->id}}">{{$horario->num_max_est }}</label>
							<input type="hidden" id="num_max_est{{$horario->id}}" class="form-control required" name="num_max_est" value="{{$horario->num_max_est }}">
						</td>
						<td>
							{{ count($horario->docente->asignaciones_docente) }}
						</td>
						<td>
							<a class="btn btn-warning" id="btn_habilityEditHorario{{$horario->id}}"	 onclick="habilityEditHorario({{$horario->id}})">Editar</a>

							<a class="btn btn-primary" style="display: none;" id="btn_actualizarHorario{{$horario->id}}"	 onclick="actualizarHorario({{$horario->id}})">Actualizar</a>
						<a class="btn btn-danger btn_cancelEdhor" style="display: none;" id="btn_cancelEdhor-{{$horario->id}}"><i class="fa fa-close"></i></a> 
						
						<a class="btn btn-success btn_detalles_horario" id="btn_details-{{ $horario->docente->idnumber }}">Detalles</a>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		</div>
		<hr>
		<a class="btn btn-danger" id="btn_del_hor_doc"><i class="fa fa-trash"></i> Eliminar Todo</a>
		<a class="btn btn-success" id="btn_conf_asig_doc"><i class="fa fa-check-square"></i> Confirmar Todo</a>
	</div>
</div>

              </div>
              <!--Fin Tab panel 1-->
              	<!--Tab panel 2-->
              <div class="tab-pane" id="tab_2">
      {!!Form::open(['route'=>'horario.store', 'method'=>'post','id'=>'myFormExpsStore'])!!}


<div class="row">
	<div class="col-md-8 col-md-offset-2 border-g">
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
					Horas Jornada Mañana
				</th>
				<th width="17%">
					Horas Jornada Tarde 
				</th>
				<th width="17%">
					No Max. Estudiantes
				</th>
			</thead>
			<tbody>
				@foreach($docentes_activos as $docente)
					<tr>
						<td>
							{{ $docente->idnumber }}
							<input value="{{$docente->idnumber}}" type="hidden" class="form-control" name="docidnumber[]" required>
						</td>
						<td>
							{{ $docente->full_name }}
						</td>
						<td>
							<input type="text" class="form-control" name="horas_a[]">
						</td>
						<td>
							<input type="text" class="form-control" name="horas_b[]">
						</td>
						<td>
							<input type="text" class="form-control" name="num_max_est[]">
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		</div>
		<hr>
		{!! Form::submit('Enviar',['class'=>"btn btn-primary"]) !!}
	</div>
</div>
{!!Form::close()!!}
             </div>
              <!--Fin Tab panel 2-->

              <!--Tab panel 3-->
              <div class="tab-pane" id="tab_3">
              	
<div class="row">
{!!Form::open(['url'=>'/docentes/horario','method'=>'get','id'=>'myFormSearchEstudiante'])!!}

	<div class="col-md-3 col-md-offset-5">
		<input type="text" placeholder="No Identificación" class="form-control" name="data_search">
	</div>
	<div class="col-md-4">
		<button type="submit" class="btn btn-success" id="btn_search_estu">
			Buscar
		</button>
		<a href="/docentes/horario/" class="btn btn-default" id="btn_seeall">
			Ver Todo
		</a>
	</div>
	{!!Form::close()!!}
 
</div>
<div class="row">
	<div class="col-md-8 col-md-offset-2 border-g">
	<div id="table_list_model"> 
      @include('myforms.frm_asignaciones_docente_estudiante_ajax')
    </div>
		<hr>
		
	</div>
</div>

              </div>
              <!--Fin Tab panel 3-->


          </div>
      </div>





@include('myforms.frm_estudiantes_asig_docente')
@include('myforms.frm_modal_asig_docent_est')


@stop
