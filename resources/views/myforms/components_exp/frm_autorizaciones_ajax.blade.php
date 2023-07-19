@foreach ($asignacion->autorizaciones as $autorizacion)
    <tr>
        <td>
            {{ $autorizacion->nombre_estudiante }}
        </td>

        <td>
            {{ $autorizacion->calidad_de }}
        </td>
        <td>
            {{ $autorizacion->tipo_proceso }}
        </td>
        <td>
            <span class="pull-center badge bg-{{ $autorizacion->estado ? 'green' : 'warning' }} dis-block ">
                {{ $autorizacion->estado ? 'Autorizado' : 'Sin autorizar' }}
            </span>
        </td>
        <td>
            @if (!$autorizacion->estado and currentUser()->id == $autorizacion->user_solicitante_id)
                <button data-id="{{ $autorizacion->id }}"
                    class="btn btn-primary btn-sm btn_editar_autorizacion">Editar</button>
                <button data-id="{{ $autorizacion->id }}"
                    class="btn btn-danger btn-sm btn_eliminar_autorizacion">Eliminar</button>
            @else
                @if ($autorizacion->estado)
                    <a href="/autorizaciones/descargar/{{ $autorizacion->id }}" target="_blank"
                        class="btn btn-info btn-sm btn_print_autorizacion">
                        Descargar</a>
                @endif
            @endif

            @if (currentUser()->hasRole('docente') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai'))
                <button data-id="{{ $autorizacion->id }}" data-estado="{{ $autorizacion->estado }}"
                    class="btn btn-{{ $autorizacion->estado ? 'default' : 'warning' }} btn-sm btn_change_estado_autorizacion">
                    {{ $autorizacion->estado ? 'Quitar Autorizado' : 'Autorizar' }}
                </button>
            @endif
            <button data-id="{{ $autorizacion->id }}"
              class="btn btn-success btn-sm btn_detalles_autorizacion">Detalles</button>
          
        </td>
    </tr>
@endforeach
