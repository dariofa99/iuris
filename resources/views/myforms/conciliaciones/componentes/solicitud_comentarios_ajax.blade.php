@foreach ($conciliacion->comentarios()->orderBy('created_at', 'desc')->get() as $key => $comentario)
    @if (currentUser()->id == $comentario->user_id ||
            $comentario->compartido == 1 ||
            currentUser()->hasRole('visitante_conciliacion') ||
            currentUser()->can('ver_notif_conciliacion'))
        <tr>

            <td class="mailbox-name">{{ $comentario->user->name }} {{ $comentario->user->lastname }}</td>



            <td class="mailbox-subject">
                {{ strlen($comentario->comentario) > 30
                    ? substr(strip_tags($comentario->comentario), 0, 40) . '...'
                    : strip_tags($comentario->comentario) }}
            </td>
            <td>
                @if ($comentario->files->count() > 0)
                    <a href="{{url('/conciliaciones/download/file/'.$comentario->files->first()->id)}}" title="Descargar archivo adjunto">
                        <i class="fas fa-paperclip"></i>
                    </a>
                @endif
            </td> 
            <td>{{ TiempoTrans($comentario->created_at) }}</td>
            <td>
                @if (currentUser()->id == $comentario->user_id || currentUser()->hasRole('amatai'))
                    <button class="btn btn-danger btn-sm btn_delete_com_con" data-id="{{ $comentario->id }}"><i
                            class="fas fa-trash"></i></button>
                    <button class="btn btn-success btn-sm btn_edit_com_con" data-id="{{ $comentario->id }}"><i
                            class="fas fa-eye"></i></button>
                @endif
            </td>
        </tr>
    @endif
@endforeach
