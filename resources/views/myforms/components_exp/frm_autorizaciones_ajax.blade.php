@if ($expediente->asignacion)
    @foreach ($expediente->asignacion->autorizaciones as $autorizacion)
        <tr>
            <td>
                {{ $autorizacion->nombre_estudiante == '' ? 'Pendiente' : $autorizacion->nombre_estudiante }}
            </td>

            <td>
                {{ $autorizacion->calidad_de == '' ? 'Pendiente' : $autorizacion->calidad_de }}
            </td>
            <td>
                {{ $autorizacion->tipo_proceso == '' ? 'Pendiente' : $autorizacion->tipo_proceso }}
            </td>
            <td>
                <span style="background-color: {{ $autorizacion->ref_estado->color  }};" class="pull-center badge dis-block ">
                    {{ $autorizacion->ref_estado ? $autorizacion->ref_estado->ref_nombre : 'Sin autorizar' }}
                </span>
            </td>
            <td>
                @if (!isset($readonly) || (isset($readonly) and !$readonly))
                    @if (
                        !$autorizacion->estado and $autorizacion->ref_estado->id == 280 and
                            currentUser()->id == $autorizacion->user_solicitante_id ||
                                (currentUser()->hasRole('dirgral') ||
                                currentUser()->hasRole('amatai')))


                        <button data-id="{{ $autorizacion->id }}"
                            class="btn btn-primary btn-sm btn_editar_autorizacion">Editar</button>
                        <button data-id="{{ $autorizacion->id }}"
                            class="btn btn-danger btn-sm btn_eliminar_autorizacion">Eliminar</button>


                    @elseif(currentUser()->hasRole('estudiante') and !$autorizacion->estado)
                        <button data-id="{{ $autorizacion->id }}"
                            class="btn btn-primary btn-sm btn_editar_autorizacion">Actualizar información</button>
                    @endif

                    @if (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai'))
                        <button data-id="{{ $autorizacion->id }}" data-estado="{{ $autorizacion->estado }}"
                            class="btn btn-{{ $autorizacion->estado ? 'default' : 'warning' }} btn-sm btn_change_estado_autorizacion">
                            {{ $autorizacion->estado ? 'Quitar Autorizado' : 'Autorizar' }}
                        </button>
                    @endif
                @endif
                <button data-id="{{ $autorizacion->id }}"
                    class="btn btn-success btn-sm btn_detalles_autorizacion">Detalles</button>
                @if ($autorizacion->estado)
                    <a href="/autorizaciones/descargar/{{ $autorizacion->id }}" target="_blank"
                        class="btn btn-info btn-sm btn_print_autorizacion">
                        Descargar</a>
                @endif


                @if (
                    !$autorizacion->estado and
                        currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai'))
                    <button data-id="{{ $autorizacion->id }}" data-estado="{{ $autorizacion->estado }}"
                        class="btn btn-{{ $autorizacion->estado_notificado ? 'info' : 'primary' }} btn-sm btn_rechazar_autorizacion">
                        {{ $autorizacion->estado_notificado ? 'Notificado' : 'Notificar' }}</button>
                    </button>
                @endif


            </td>
        </tr>
    @endforeach
@endif
