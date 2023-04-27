@foreach($conciliacion->comentarios as $key => $comentario)
@if(currentUser()->id == $comentario->user_id || $comentario->compartido ==  1)

<tr>
   <td>{{$comentario->comentario}}</td>
   <td>{{$comentario->user->name}} {{$comentario->user->lastname}}</td>
   <td>{{$comentario->created_at}}</td>
   <td>
       @if(currentUser()->id == $comentario->user_id || currentUser()->hasRole('amatai'))
       <button class="btn btn-danger btn-sm btn_delete_com_con" data-id="{{$comentario->id}}">Eliminar</button>
       
      
      
      <button class="btn btn-primary btn-sm btn_edit_com_con" data-id="{{$comentario->id}}">Editar</button>
        @endif
           </td>
</tr>
@endif
@endforeach