<div class="row">
    <div class="col-md-3">
        @php
            $label = 'Presentar demanda';
            if ($expediente->asignacion->procesojud_id == 244) {
                $label = 'Presentar subsanación';
            } elseif ($expediente->asignacion->procesojud_id == 246) {
                $label = 'Presentar respuesta juzgado';
            } elseif ($expediente->asignacion->procesojud_id == 247) {
                $label = 'Presentar respuesta rechazo';
            }
        @endphp
        @if (
            $expediente->asignacion->procesojud_id !== 243 
            and (currentUser()->hasRole('estudiante') ||
                currentUser()->hasRole('amatai') 
                || currentUser()->hasRole('diradmin') 
                || currentUser()->hasRole('dirgral')))
            <button data-estado="{{ $expediente->asignacion->procesojud_id }}" class="btn btn-sm btn-primary btn-block"
                id="btn_ges_judexp">
                <span>{{ $label }}</span>
            </button>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        Estado actual: <label> <br>
            <span class="badge badge-success"
                style="font-size:15px;background-color: {{ $expediente->asignacion->estadoProcJudicial->color }} !important">
                {{ $expediente->asignacion->estadoProcJudicial->ref_nombre }}
            </span>

        </label>

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <th>
                    Comentario
                </th>
                {{--    <th>
                    Estado
                </th> --}}
                <th>
                    Fecha creación
                </th>
                <th>
                    Archivo
                </th>
            </thead>
            <tbody>
                @foreach ($expediente->asignacion->procesosjudiciales()->orderBy('created_at', 'desc')->get() as $key => $projudicial)
                    <tr>
                        <td>
                            {{ $projudicial->comentario }}
                        </td>
                        {{--   <td>
                            {{ $projudicial->estado->ref_nombre }}

                        </td> --}}
                        <td>
                            {{ getSmallDateWithHour($projudicial->created_at) }}

                        </td>
                        <td>
                            @forelse($projudicial->files as $key => $file)
                                <a target="_blank" href="{{ route('file.download', $file->id) }}">
                                    {{ $file->original_name }} </a> <br>
                            @empty
                                <label> Sin archivos</label>
                            @endforelse

                        </td>
                        <td>
                            <input data-id="{{ $projudicial->id }}"
                                class="btn btn-success btn-sm btn-block btn_detallesprjex" type="button"
                                value="Detalles">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
