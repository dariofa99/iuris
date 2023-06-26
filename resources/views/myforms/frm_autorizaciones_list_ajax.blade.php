<table class="table" id="table_list_autorizaciones">
<thead>
<th>
Fecha de creación
</th>
<th>
Nombre del estudiante
</th>
<th>
No. Radicado
</th>
<th>
Tipo de proceso
</th>
<th>
No. Expediente
</th>
<th>
Estado
</th>
</thead>
<tbody>
@foreach ($autorizaciones as $autorizacion)
    <tr>
<td>
{{$autorizacion->created_at}}
</td>
<td>
{{$autorizacion->nombre_estudiante}}
</td>

<td>
{{$autorizacion->num_radicado}}
</td>
<td>
{{$autorizacion->tipo_proceso}}
</td>
<td>
{{$autorizacion->asignacion->asigexp_id}}
</td>
<td>
  <span class="pull-center badge bg-{{$autorizacion->estado ? 'success':'warning'}} dis-block ">
{{$autorizacion->estado ? 'Autorizado':'Sin autorizar'}}
 </span>
</td>
<td>
<a href="/expedientes/{{$autorizacion->asignacion->asigexp_id}}/edit" class="btn btn-success btn-sm btn_detalles_autorizacion">
Ir al caso</a>
<button data-id="{{$autorizacion->autorizacion_id}}" data-estado="{{$autorizacion->estado}}" class="btn btn-{{$autorizacion->estado ? 'default':'warning'}} btn-sm btn_change_estado_autorizacion">
{{$autorizacion->estado ? 'Quitar Autorizado':'Autorizar'}}
</button>
@if($autorizacion->estado)
<a href="/autorizaciones/descargar/{{$autorizacion->autorizacion_id}}" target="_blank" class="btn btn-success btn-sm btn_print_autorizacion">
Descargar</a>
@endif
</td>
</tr> 
@endforeach
</tbody>
</table>
<hr>
{{ $autorizaciones->appends(request()->query())->links() }}