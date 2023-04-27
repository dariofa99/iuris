@extends('layouts.dashboard')


@section('titulo_general')
Corte
@endsection

@section('titulo_area')
Listar Cortes
@endsection


@section('area_forms')

@include('msg.success')

@include('msg.ajax.danger') 
@include('msg.ajax.success') 

<div class="row">
	<div class="col-md-2">
		<button type="button" class="btn" id="btn_create_segmento"> <i class="fa fa-list"></i> Nuevo Corte</button>
	</div>
	{!!Form::open(['url'=>'/segmentos/','method'=>'get','id'=>'myFormSearchPeriodo'])!!}

	<div class="col-md-3">
		<select name="datatype" id="datatype" class="form-control required">
			<option value="">Seleccione tipo de busqueda.</option>
			<option value="name">Nombre</option>
			<option value="fecha_ini">Fecha de Inicio</option>
			<option value="fecha_fin">Fecha Fin</option>
		</select>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control required" name="data_search" id="data_search">
	</div>
	<div class="col-md-2">
		<button type="submit" class="btn btn-success " id="btn_search_estu">
			Buscar
		</button>
		<a href="/segmentos/" class="btn btn-default" id="btn_seeall">
			Ver Todo
		</a>
	</div>
	
	{!!Form::close()!!}
	<div class="col-md-3">

		<div class="pull-right">
			Activa Final de Corte:
			<i class="fa fa-toggle-on switch-off" id="switch_act_fc"></i>  
		</div>
	</div>
</div>
<div class="row">	

		<div class="col-md-12">	 
			<div id="table_list_model">
				@include('myforms.frm_segmentos_list_ajax')
			</div>



		</div>

</div> 
@include('myforms.frm_segmento_create')
@include('myforms.frm_segmento_edit')

@stop
