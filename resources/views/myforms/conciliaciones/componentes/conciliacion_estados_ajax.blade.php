@foreach($conciliacion->estados()->orderBy('created_at','desc')->get() as $key => $estado)
<tr>
   <td>{{$estado->type_status->ref_nombre}}</td>
   <td>{{$estado->concepto}}</td>
   <td>{{$estado->user->name}} {{$estado->user->lastname}}</td>
   <td>{{getSmallDateWithHour($estado->created_at)}}</td>
   {{-- <td>
    <button type="button" class="btn btn-warning btn-sm btn_descargar_rep_pdf" data-estado_id="{{$estado->type_status_id}}"  data-id="{{$estado->id}}">Ver actas</button>
    
    @if(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai'))
    <button type="button" class="btn btn-primary btn-sm btn_compartir_rep_pdf" data-estado_id="{{$estado->type_status_id}}"  data-id="{{$estado->id}}">Compartir</button></td>
@endif
</td> --}}
   
    {{--   <td>
    @if(((currentUser()->hasRole('amatai'))))
        <button class="btn btn-danger btn-sm btn_delete_est_con" data-id="{{$estado->id}}">
            Eliminar
        </button>
    
        <button class="btn btn-primary btn-sm btn_edit_est_con" data-id="{{$estado->id}}">
            Editar</button>
        @endif
    </td> --}}
</tr>
@endforeach