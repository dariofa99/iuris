@extends('layouts.dashboard')


@section('titulo_general')
Conciliaciones
@endsection

@section('titulo_area')
{{-- Listado de Estudiantes --}}
@endsection


@section('area_forms')

@include('msg.success')

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" class="tab-btn-show-notas" data-toggle="tab">Turnos estudiantes</a></li>
{{-- @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
              <li><a href="#tab_2" class="tab-btn-show-notas" data-toggle="tab">Estudiantes por Asignar</a></li>
@endif --}}
              <li><a href="#tab_3" class="tab-btn-show-notas" data-toggle="tab">Nueva conciliación</a></li>
 
            </ul>
            <div class="tab-content">

			<!--Tab panel 1-->
              <div class="tab-pane active" id="tab_1">
					<div class="row">
	<div class="col-md-6">
		{!!Form::open(['route'=>'conciliaciones.index', 'method'=>'GET'])!!}
		<table class="normal-table">
			<tr>
				<td>
					Busqueda
				</td>
				<td>

				</td>
				<td>
					{!!Form::select('data_search',$cursando,null,['class' => 'form-control input-search  selectpicker input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>'select_data_cursando'] ); !!}
				</td>
				<td>
					<input class="btn btn-success" type="submit" name="buscar" value="Buscar">
					<a class="btn btn-default" href="/conciliaciones">Ver Todo</a>
				</td>
			</tr>
		</table>
		{!!Form::close()!!}
	</div>
	<div class="col-md-2 col-md-offset-4">

		<table class="table-colors">
			<tr>
				<td>
					<label class="badge color-amarillo ">{{$colores[0]->amarillo}}</label>
				</td>
				<td>
					<label class="badge color-rojo">{{$colores[0]->rojo}}</label>
				</td>
				<td>
					<label class="badge color-verde">{{$colores[0]->verde}}</label>
				</td>
				<td>
					<label class="badge color-gris">{{$colores[0]->gris}}</label>
				</td>
				<td>
					<label class="badge color-azul">{{$colores[0]->azul}}</label>
				</td>
				<td>
					<label class="badge color-morado">{{$colores[0]->morado}}</label>
				</td>
			</tr>
		</table>

	</div>
</div>
<hr>

<div class="row">
	<div class="col-md-12">
<div class="box-body table-responsive no-padding">
		<table class="normal-table table-list-est-tur">
			<thead>
			<th width="10%">
					No. Documento
				</th>

				<th width="20%">
					Estudiante
				</th>
				
				<th width="15%">
					Color
				</th>
				<th>
					Curso
				</th>
				<th>
					Horario
				</th>
				{{-- <th>
					Oficina
				</th>
				<th>
					Dia
				</th> --}}

				<th>
					Acciones
				</th>

			</thead>
			<tbody>
				@foreach($turnos as $turno)
				@if(isset($data_search) and $data_search!='')
				@if($turno->turnosestudiantes->cursando_id == $data_search)
					<tr>
					<td>
					 {{$turno->turnosestudiantes->idnumber }}
					</td>

					<td align="left">
						<input type="hidden" value="{{$turno->turnosestudiantes->id}}" id="estudiante_id{{$turno->id}}">
						{{$turno->turnosestudiantes->name }} {{$turno->turnosestudiantes->lastname }} 

					</td>
				
					<td>
						{!!Form::select('color_id',$ref_color,$turno->color->id,['class' => 'form-control  input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"color_id$turno->id",'style'=>'display:none'] ); !!}



						<label id="label_color{{$turno->id}}" class="label dis-block {{$turno->getColorTurno($turno->color->ref_value) }}">{{$turno->color->ref_nombre}}</label>
					</td>
					<td>
						{!!Form::select('cursando_id',$cursando,$turno->turnosestudiantes->curso->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"cursando_id$turno->id",'style'=>'display:none'] ); !!}

						<label id="label_cursando{{$turno->id}}"> {{$turno->turnosestudiantes->curso->ref_nombre }} </label>
					</td>
					<td>
						{!!Form::select('horario_id',$ref_horarios,$turno->horario->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"horario_id$turno->id",'style'=>'display:none'] ); !!}
						<label id="label_horario{{$turno->id}}">
						{{$turno->horario->ref_nombre }}
					</label>
					</td>
				{{-- 	<td>
						{!!Form::select('trnid_oficina',$oficinas,$turno->oficina->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"trnid_oficina$turno->id",'style'=>'display:none'] ); !!}
						<label id="label_trnid_oficina{{$turno->id}}">						
						{{$turno->oficina->nombre }}
					</label>
					</td>

						<td>
						{!!Form::select('trnid_dia',$dias,$turno->dia->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"trnid_dia$turno->id",'style'=>'display:none'] ); !!}
						<label id="label_trnid_dia{{$turno->id}}">						
						{{$turno->dia->ref_nombre }}
					</label>
					</td> --}}

<td>
	<a class="btn btn-success btn_detalles_turno" id="btn_detalles_turno-{{$turno->id}}"><i class="fa fa-edit"> </i>Detalles</a>

</td>
{{-- @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
					<td>
						<a style="display: none;" class="btn btn-success btn_updatecolor" id="btnUpdatecolor_{{$turno->id}}"><i class="fa fa-check-square"> </i> Actualizar</a>

						<a style="display: none;" class="btn btn-warning" id="btn_hideupdatecolor{{$turno->id}}" onclick="hideEditColor({{$turno->id}})"><i class="fa fa-close"> </i></a>

						<a class="btn btn-primary" id="btn_habilityupdatecolor{{$turno->id}}" onclick="habilityEditColor({{$turno->id}})"><i class="fa fa-edit"> </i>Editar</a>

						<a class="btn btn-danger btn_delete_turno" id="btn_delete_turno-{{$turno->id}}"><i class="fa fa-edit"> </i>Eliminar</a>

					</td>
@endif --}}
				</tr>
				@endif

				@else
				<tr>
					<td>
					 {{$turno->turnosestudiantes->idnumber }}
					</td>
					
					<td align="left">
						<input type="hidden" value="{{$turno->turnosestudiantes->id}}" id="estudiante_id{{$turno->id}}">
						{{$turno->turnosestudiantes->name }} {{$turno->turnosestudiantes->lastname }}
					
					</td>
				
					<td>
						{!!Form::select('color_id',$ref_color,$turno->color->id,['class' => 'form-control  input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"color_id$turno->id",'style'=>'display:none'] ); !!}



						<label id="label_color{{$turno->id}}" class="label dis-block {{$turno->getColorTurno($turno->color->ref_value) }}">{{$turno->color->ref_nombre}}</label>  
					</td>
					<td>
						{!!Form::select('cursando_id',$cursando,$turno->turnosestudiantes->curso->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"cursando_id$turno->id",'style'=>'display:none'] ); !!}

						<label id="label_cursando{{$turno->id}}"> {{$turno->turnosestudiantes->curso->ref_nombre }} </label>
					</td>

					<td>
						{!!Form::select('horario_id',$ref_horarios,$turno->horario->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"horario_id$turno->id",'style'=>'display:none'] ); !!}
						<label id="label_horario{{$turno->id}}">
						{{$turno->horario->ref_nombre }}
					</label>
					</td>
					{{-- 	<td>
						{!!Form::select('trnid_oficina',$oficinas,$turno->oficina->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"trnid_oficina$turno->id",'style'=>'display:none'] ); !!}
						<label id="label_trnid_oficina{{$turno->id}}">
						
						{{$turno->oficina->nombre }}
					</label>
					</td>
						<td>
						{!!Form::select('trnid_dia',$dias,$turno->dia->id,['class' => 'form-control input-select', 'data-live-search'=>'true', 'required' => 'required','id'=>"trnid_dia$turno->id",'style'=>'display:none'] ); !!}
						<label id="label_trnid_dia{{$turno->id}}">						
						{{$turno->dia->ref_nombre }}
					</label>
					</td> --}}
                    <td>
  <a class="btn btn-success btn_detalles_estudiante" data-idnumber="{{$turno->turnosestudiantes->id}}" id="btn_detalles_turno-{{$turno->id}}">
    Detalles</a>

</td>
{{-- @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
					<td>
						<a style="display: none;" class="btn btn-success btn_updatecolor" id="btnUpdatecolor_{{$turno->id}}"><i class="fa fa-check-square"> </i> Actualizar</a>

						<a style="display: none;" class="btn btn-warning" id="btn_hideupdatecolor{{$turno->id}}" onclick="hideEditColor({{$turno->id}})"><i class="fa fa-close"> </i></a>

						<a class="btn btn-primary" id="btn_habilityupdatecolor{{$turno->id}}" onclick="habilityEditColor({{$turno->id}})"><i class="fa fa-edit"> </i>Editar</a>

						<a class="btn btn-danger btn_delete_turno" id="btn_delete_turno-{{$turno->id}}"><i class="fa fa-edit"> </i>Eliminar</a>

					</td>
@endif --}}
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>
		</div>
		<hr>
	</div>
</div>

              </div>
              <!--Fin Tab panel 1-->

@if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
              <!--Tab panel 2-->
              <div class="tab-pane" id="tab_2">

{!!Form::open(['url'=>'/turnos/','method'=>'get','id'=>'myFormSearchEstudiante'])!!}
<div class="row">
	<div class="col-md-3 col-md-offset-5">
		<input type="text" placeholder="No Identificación" class="form-control" name="data_search">
	</div>
	<div class="col-md-4">
		<button type="submit" class="btn btn-success" id="btn_search_estu">
			Buscar
		</button>
		<a href="/turnos/" class="btn btn-default" id="btn_seeall">
			Ver Todo
		</a>
	</div>
</div>
	{!!Form::close()!!}
<div class="row">
	<div class="col-md-8 col-md-offset-2 border-g">
		<div id="table_list_model">
      	@include('myforms.frm_turnos_students_list_ajax')
    	</div>
		<hr>

	</div>
</div>

              </div>
              <!--Fin Tab panel 2-->
@endif
<!--Tab panel 3-->
              <div class="tab-pane" id="tab_3">
      <div class="row">

<form id="myformSearchAutorizaciones" action="/conciliaciones">
<div class="col-md-4">
<select name="tipo_busqueda" id="tipo_busqueda" class="form-control" placeholder="Seleccione..." required="required">
  <option value="">Seleccione...</option>
   <option value="num_identificacion">Nombres</option>
  <option value="num_radicado">Número de cédula</option>
 
  </select>
</div>
<div class="col-md-4">
<input class="form-control input-search" required="required" id="input_data" placeholder="No de Documento" name="data" type="text">
</div>
<div class="col-md-4">   
<button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Buscar </button>
<button type="button" id="btn_ver_todo_conciliaciones" class="btn btn-default"> Ver todo </button>
<button type="button"  class="btn btn-primary btn-sm" id="btn_crear_conciliacion">Nueva conciliación</button>
</div>
</form>


</div>
<div class="row">
	
<div class="col-md-12 table-responsive no-padding" id="content_list_conciliaciones">

<table class="table">
<thead>
<th>Fecha</th>
<th>Hora</th>
<th>Estudiante</th>
</thead>
<tbody>
@foreach ($conciliaciones as $conciliacion )
    <tr>
    <td>{{$conciliacion->fecha_cita}}</td>
    <td>{{$conciliacion->hora_inicio}}</td>
    <td>Estudiante 1</td>
    <td width="15%"><button class="btn btn-primary btn-sm">Editar</button>
    <button class="btn btn-danger btn-sm">Eliminar</button></td>
    </tr>
@endforeach
</tbody>
</table>

</div>
</div>
              </div><!--Fin Tab panel 3-->
          </div>
      </div>

@include('myforms.conciliaciones.frm_modal_estudiante_detalles')

@include('myforms.conciliaciones.frm_modal_conciliacion_create')

@stop
