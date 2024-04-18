@foreach($conciliacion->comentarios()->orderBy('created_at','desc')->get() as $key => $comentario)
@if(currentUser()->id == $comentario->user_id || $comentario->compartido ==  1
|| currentUser()->hasRole('visitante_conciliacion') || currentUser()->can('ver_notif_conciliacion'))

<tr>
   <td>{!! trim(strip_tags($comentario->asunto)) !!}</td>
   <td>{{$comentario->user->name}} {{$comentario->user->lastname}}</td>
   <td>{{getSmallDateWithHour($comentario->created_at)}}</td>
   <td>
       @if(currentUser()->id == $comentario->user_id || currentUser()->hasRole('amatai'))
       <button class="btn btn-danger btn-sm btn-block btn_delete_com_con" data-id="{{$comentario->id}}">Eliminar</button>
       
      
      
      <button class="btn btn-success btn-sm btn_edit_com_con btn-block" data-id="{{$comentario->id}}">Detalles</button>
        @endif
           </td>
</tr>
@endif
@endforeach