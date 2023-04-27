@extends('layouts.dashboard')
@section('titulo_area')
Oficina: {{$oficina->nombre}}
<input type="hidden" id="oficina_id" value="{{$oficina->id}}">
@endsection

@section('area_forms')

@include('msg.success')
<div class="row">

<form id="myformSearchAutorizaciones" action="/autorizaciones">
<div class="col-md-4">
<select name="tipo_busqueda" id="tipo_busqueda" class="form-control" placeholder="Seleccione..." required="required">
  <option value="">Seleccione un día...</option>
		@foreach ($dias as $key => $value)
			<option value="{{$key}}">{{$value}}</option>
		@endforeach
  </select>
</div>
<div class="col-md-4">
<input class="form-control input-search" required="required" id="input_data" placeholder="No de Documento" name="data" type="text">
</div>
<div class="col-md-4">
<button type="submit" class="btn btn-success"><i class="fa fa-search"> </i> Buscar </button>
<button type="button" id="btn_ver_todo_autorizaciones" class="btn btn-default"> Ver todo </button>

</div>
</form>


</div>
<div class="row">
<div class="col-md-12 table-responsive no-padding" id="content_list_oficinas">

<table class="table" id="list_students_oficinas" >	
				<thead>	
					<th>Nombre</th>
					<th>Curso</th>
                    <th>Correo</th>
					<th>Teléfono</th>
					<th>Día</th>
					<th>Acciones</th>
				</thead>
				<tbody>	
               @include('myforms.frm_oficinas_ver_ajax')			
						

				</tbody>

						

			</table>
			

</div>
</div>

@include('myforms.frm_modal_asig_notas_ext')

              @stop