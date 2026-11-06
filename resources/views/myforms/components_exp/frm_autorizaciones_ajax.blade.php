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
                <span
                    @if ($autorizacion->ref_estado) style="color: white; background-color: {{ $autorizacion->ref_estado->color }};" @endif
                    class="iuris-autorizacion-status">
                    {{ $autorizacion->ref_estado ? $autorizacion->ref_estado->ref_nombre : 'Sin autorizar' }}
                </span>
            </td>
            <td class="iuris-autorizacion-actions-cell">
                <div class="iuris-autorizacion-actions">
                    @if (!isset($readonly) || (isset($readonly) and !$readonly))
                        @if (
                            !$autorizacion->estado and
                                $autorizacion->ref_estado->id == 280 and
                                currentUser()->id == $autorizacion->user_solicitante_id ||
                                    (currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai')))
                            <button data-id="{{ $autorizacion->id }}"
                                    class="btn btn-sm iuris-autorizacion-action--edit btn_editar_autorizacion">Editar</button>
                            <button data-id="{{ $autorizacion->id }}"
                                    class="btn btn-sm iuris-autorizacion-action--delete btn_eliminar_autorizacion">Eliminar</button>
                        @elseif(currentUser()->hasRole('estudiante') and !$autorizacion->estado)
                            <button data-id="{{ $autorizacion->id }}"
                                    class="btn btn-sm iuris-autorizacion-action--edit btn_editar_autorizacion">
                                {{ $autorizacion->estado_id == 283 ? 'Volver a solicitar' : 'Actualizar información' }}
                            </button>
                        @endif

                        @if (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai'))
                            <button data-id="{{ $autorizacion->id }}" data-estado="{{ $autorizacion->estado }}"
                                    class="btn btn-sm {{ $autorizacion->estado ? 'iuris-autorizacion-action--revoke' : 'iuris-autorizacion-action--authorize' }} btn_change_estado_autorizacion">
                                {{ $autorizacion->estado ? 'Quitar Autorizado' : 'Autorizar' }}
                            </button>
                        @endif
                    @endif
                    <button data-id="{{ $autorizacion->id }}"
                        class="btn btn-sm iuris-autorizacion-action--details btn_detalles_autorizacion">Detalles</button>
                    @if ($autorizacion->estado)
                        <a href="/autorizaciones/descargar/{{ $autorizacion->id }}" target="_blank"
                                class="btn btn-sm iuris-autorizacion-action--download btn_print_autorizacion">
                            Descargar</a>
                    @endif
                    @if (
                        !$autorizacion->estado and
                            currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai'))
                        <button data-id="{{ $autorizacion->id }}" data-estado="{{ $autorizacion->estado }}"
                            class="btn btn-sm {{ $autorizacion->estado_notificado ? 'iuris-autorizacion-action--notified' : 'iuris-autorizacion-action--notify' }} btn_rechazar_autorizacion">
                            {{ $autorizacion->estado_notificado ? 'Notificado' : 'Notificar' }}
                        </button>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
@endif
