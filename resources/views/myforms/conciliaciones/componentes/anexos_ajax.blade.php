@foreach ($conciliacion->files as $key => $file)
    <tr class="files" data-type="{{ $file->pivot->category_id }}">
        <td>
            {{ $file->pivot->concepto }}
        </td>
        <td>
            {{ $file->original_name }}
        </td>
        <td>
            {{ $file->userinconciliacion[0]->name }} {{ $file->userinconciliacion[0]->lastname }}
        </td>

        <td width="4%">
            <a title="Descargar documento" class="btn btn-block btn-warning" toltip="Vista previa del  documento"
                target="_blank" href="/conciliaciones/download/file/{{ $file->pivot->file_id }}">
                <i class="fa fa-download">
                    Descargar
                </i>
            </a>
            @if (currentUserInConciliacion($conciliacion->id, ['autor']))
                <a class="btn btn-block btn-danger btn_delete_anxcon" data-file="{{ $file->pivot->file_id }}"
                    title="Eliminar documento" href="#">
                    <i class="fa fa-trash"> Eliminar</i>
                </a>
            @endif

        </td>




    </tr>
@endforeach
