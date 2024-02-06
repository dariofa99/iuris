 @foreach ($conciliacion->files as $key => $file)
                    <tr>
                        <td>

                            {{ $file->pivot->concepto }}
                        </td>
                        <td>
                            {{ $file->categoryInConciliacion()->orderBy('created_at', 'desc')->first()->ref_nombre }}
                        </td>
                        <td>
                            {{ $file->userinconciliacion()->orderBy('created_at', 'desc')->first()->name }}
                            {{ $file->userinconciliacion()->orderBy('created_at', 'desc')->first()->lastname }}
                            <small>
                                <i>
                                    ({{ $file->userinconciliacion()->orderBy('created_at', 'desc')->first()->role()->first()->display_name }})
                                </i>
                            </small>
                        </td>
                        <td>
                            <small>
                                <a target="_blank" href="/conciliaciones/download/file/{{ $file->id }}">
                                    {{ $file->original_name }}
                                </a>
                            </small>
                        </td>
                        <td>
                            <button data-concepto="{{$file->pivot->concepto}}" data-id="{{ $file->id }}" class="btn btn-warning btn_compartir_doc">
                                Compartir
                            </button>
                        </td>
                    </tr>
                @endforeach