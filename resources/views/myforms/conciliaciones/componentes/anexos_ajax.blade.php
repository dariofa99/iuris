        @php $order = 0; @endphp
        @foreach ($conciliacion->files as $key => $file)
            @php $order++; @endphp
            <tr class="files" data-type="{{ $file->pivot->category_id }}" style="--order: {{ $order }}">
                <td>
                    <div class="concepto-badge">
                        <i class="fas fa-tag"></i>
                        {{ $file->pivot->concepto }}
                      
                    </div>
                </td>
                <td>
                    <div class="file-name">
                        {{ $file->original_name }}
                    </div>
                    <small style="color: var(--gray); font-size: 0.7rem;">
                        <i class="far fa-clock"></i> Subido recientemente
                    </small>
                </td>
                <td>
                    <div class="user-info">
                       
                        <div>
                            <strong>{{ $file->userinconciliacion[0]->name ?? 'Usuario' }}
                                {{ $file->userinconciliacion[0]->lastname ?? '' }}</strong>
                        </div>
                    </div>
                </td>
                <td width="15%">
                    <div class="actions-group">
                        <a href="/conciliaciones/download/file/{{ $file->pivot->file_id }}"
                            class="btn btn-warning btn-xs d-block mt-1" target="_blank"
                            data-tooltip="Vista previa y descarga del documento">
                            <i class="fas fa-download"></i>
                            Descargar
                        </a>

                        @if (currentUserInConciliacion($conciliacion->id, ['autor']))
                            <a href="#" class="btn btn-danger btn-xs d-block mt-1 btn_delete_anxcon"
                                data-file="{{ $file->pivot->file_id }}"
                                data-tooltip="Eliminar documento permanentemente">
                                <i class="fas fa-trash-alt"></i>
                                Eliminar
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
