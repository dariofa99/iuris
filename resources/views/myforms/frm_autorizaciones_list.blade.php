@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')
<div class="row">

<form id="myformSearchAutorizaciones" action="/autorizaciones">
<div class="col-md-4">
<select name="tipo_busqueda" id="tipo_busqueda" class="form-control" placeholder="Seleccione..." required="required">
  <option value="">Seleccione...</option>
  <option value="num_radicado">Número de radicado</option>
  <option value="num_identificacion">Número de documento estudiante</option>
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
<div class="col-md-12 table-responsive no-padding" id="content_list_autorizaciones">
@include('myforms.frm_autorizaciones_list_ajax')
</div>
</div>

              @stop