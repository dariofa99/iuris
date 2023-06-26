@extends('layouts.dashboard')


@section('titulo_general')
periodos
@endsection

@section('titulo_area')
Listar periodos
@endsection


@section('area_forms')
 
@include('msg.success') 
@include('msg.danger') 
<div class="row">
	<div class="col-md-3">
		<button type="button" class="btn" id="btn_create_periodo"> <i class="fa fa-list"></i> Nuevo Periodo</button>
	</div>
	{!!Form::open(['url'=>'/periodos/','method'=>'get','id'=>'myFormSearchPeriodo'])!!}

	<div class="col-md-3">
		<select name="datatype" id="datatype" class="form-control required">
			<option value="">Seleccione tipo de busqueda.</option>
			<option value="name">Nombre</option>
			<option value="fecha_ini">Fecha de Inicio</option>
			<option value="fecha_fin">Fecha Fin</option>
		</select>
	</div>
	<div class="col-md-3">
		<input type="text" class="form-control required" name="data_search" id="data_search">
	</div>
	<div class="col-md-3">
		<button type="submit" class="btn btn-success " id="btn_search_estu">
			Buscar
		</button>
		<a href="/periodos/" class="btn btn-default" id="btn_seeall">
			Ver Todo
		</a>
	</div>
	
	{!!Form::close()!!}
</div>

<div class="row">	

		<div class="col-md-12">	 
			<div id="table_list_model">
				@include('myforms.frm_periodos_list_ajax')
			</div>
			
			
		</div>

</div>
@include('myforms.frm_periodo_create')
@include('myforms.frm_periodo_edit')

@stop
