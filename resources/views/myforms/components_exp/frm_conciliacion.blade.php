@if(currentUser()->hasRole("estudiante"))  
  
<div class="row">
<div class="col-md-3">
@if(count($expediente->conciliaciones)<=0)
<button type="button" class="btn btn-primary btn-block btn-sm" id="btn_nueva_conciliacion">
    Crear conciliación
</button>
@endif
</div>
</div>
@endif

@if(count($expediente->conciliaciones)>0)
<div class="row">
<div class="col-md-12">
<table class="table" id="table_list_">
<thead>
<th>
Número conciliación
</th>
<th>
Estado
</th>

</thead>
<tbody>
<tr>
    <td>
        {{$expediente->conciliaciones[0]->num_conciliacion}}
    </td>
    <td>
        {{$expediente->conciliaciones[0]->estado->ref_nombre}}
    </td>
    <td>
      <a class="btn btn-primary" href="/conciliaciones/{{$expediente->conciliaciones[0]->id}}/edit">Editar</a> 
    </td>
</tr>
</tbody>
</table>
</div>
</div>
@endif
