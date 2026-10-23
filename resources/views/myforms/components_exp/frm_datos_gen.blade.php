@php
    $diasParaRespuesta = 0;
    $fechaAsignacion = $expediente->asignacion->fecha_asig ?? null;
    $fechaCortePosesion = \Carbon\Carbon::createFromFormat('d/m/Y', '22/10/2026')->startOfDay();

    if ($fechaAsignacion && \Carbon\Carbon::parse($fechaAsignacion)->startOfDay()->gt($fechaCortePosesion)) {
        $diasParaRespuesta = $expediente->getDaysForPosesion();
    }

    $documentosPosesion = $expediente->documentos()->get();

@endphp

@if ($fechaAsignacion && \Carbon\Carbon::parse($fechaAsignacion)->startOfDay()->gt($fechaCortePosesion) and $expediente->exptipoproce_id == 3 and $expediente->expestado_id == 1)
    <div class="row dofi-deadline-row">
        <div class="col-12">
            <section class="dofi-deadline-panel" style="background-color: antiquewhite !important"
                aria-label="Fecha límite de respuesta">

                @if ($documentosPosesion->isNotEmpty())
                    <label class="iuris-possession-upload">

                        <div class="iuris-possession-documents" aria-label="Documentos de posesión cargados">
                            @foreach ($documentosPosesion as $documento)
                                {{ $documento->categoryInExpediente->first()->ref_nombre ?? 'Documento de posesión' }}
                                <a href="/conciliaciones/download/file/{{ $documento->id }}" target="_blank"
                                    rel="noopener noreferrer">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                    <span>{{ $documento->original_name }}</span>
                                    <small>{{ number_format($documento->size / 1048576, 2) }} MB</small>
                                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                </a>
                                <i id="btn_delete_doc" data-id="{{ $documento->pivot->id }}"
                                    style="background-color: #a42626;
                                            width: 20px;
                                            border-radius: 3px;
                                            text-align:center;
                                            color: rgb(255, 255, 255)">
                                    <i class="fas fa-trash"></i></i>
                            @endforeach
                        </div>
                    </label>
                @else
                    <div class="dofi-deadline-info">
                        <span class="dofi-deadline-label">Fecha límite de respuesta</span>
                        <strong id="lbl_expfecha_res">
                            {{ getLongDate($expediente->expfechalimite) }}
                        </strong>
                    </div>

                    <div class="dofi-deadline-actions">
                        @if ($diasParaRespuesta > 0)
                            <span id="lbl_dias_para_respuesta" class="badge-asistencia high" aria-live="polite">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                Faltan {{ $diasParaRespuesta }} {{ $diasParaRespuesta === 1 ? 'día' : 'días' }}
                            </span>
                        @elseif ($diasParaRespuesta === 0)
                            <span id="lbl_dias_para_respuesta" class="badge-asistencia medium" aria-live="polite">
                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                Vence hoy
                            </span>
                        @else
                            <span id="lbl_dias_para_respuesta" class="badge-asistencia low" aria-live="polite">
                                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                Vencida hace {{ abs($diasParaRespuesta) }}
                                {{ abs($diasParaRespuesta) === 1 ? 'día' : 'días' }}
                            </span>
                        @endif

                        @if (
                            !$readonly and
                                (currentUser()->hasRole('diradmin') or currentUser()->hasRole('dirgral') or currentUser()->hasRole('amatai')))
                            <a class="btn btn-sm dofi-deadline-edit" data-toggle="modal" data-target="#fechalimitres">
                                <i class="fa fa-pen" aria-hidden="true"></i>
                                Cambiar fecha
                            </a>
                        @endif
                        @if (
                            !$readonly and
                                (currentUser()->hasRole('estudiante') or
                                    currentUser()->hasRole('diradmin') or
                                    currentUser()->hasRole('dirgral') or
                                    currentUser()->hasRole('amatai')))
                            <a style="background-color: #d9c022;" class="btn btn-sm dofi-deadline-edit"
                                id="btn_subir_respuesta">
                                <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                Subir documento de posesión
                            </a>
                        @endif
                    </div>
                @endif




            </section>
        </div>
    </div>
@endif

<div class="row">
    <input type="hidden" value="{{ $expediente->id }}" id="expediente_id" name="expediente_id">
    @if ($expediente->getAsignacion())
        <input type="hidden" value="{{ $expediente->getAsignacion()->fecha_asig }}" id="expediente_fecha_asig"
            name="fecha_asig">
        <input type="hidden" value="{{ $expediente->getAsignacion()->id }}" id="id_asig" name="id_asig">
    @endif

    @php
        $fotoEstudiante =
            $expediente->estudiante->image && is_file(public_path('thumbnails/' . $expediente->estudiante->image))
                ? asset('thumbnails/' . $expediente->estudiante->image)
                : asset('thumbnails/default.jpg');
    @endphp
    <div class="col-12">
        <section class="iuris-student-overview">
            <div class="iuris-student-details">
                <div class="iuris-student-profile">
                    <img src="{{ $fotoEstudiante }}"
                        alt="Foto de {{ $expediente->estudiante->name }} {{ $expediente->estudiante->lastname }}"
                        class="iuris-student-photo">

                    <div class="iuris-student-identity">
                        <span class="iuris-student-label">Estudiante asignado</span>
                        <div id="inputestudianteasignado" class="iuris-student-name">
                            {{ $expediente->estudiante->name }} {{ $expediente->estudiante->lastname }}
                        </div>

                        <div id="contselecestcasos" class="iuris-student-selector" style="display: none">
                            <select data-live-search="true" disabled required id="selectexpidnumberest"
                                class="required form-control disabled-fun3 selectpicker"
                                aria-label="Cambiar estudiante asignado">
                                @foreach ($estudiantes as $key => $estudiante)
                                    <option {{ $expediente->expidnumberest != $estudiante['idnumber'] ?: 'selected' }}
                                        value="{{ $estudiante['idnumber'] }}">
                                        {{ $estudiante['full_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="oldexpidnumberest" value="{{ $expediente->expidnumberest }}"
                            id="oldexpidnumberest">
                        <input type="hidden" name="expidnumberest" id="idnumberest" disabled>

                        @if (currentUser()->hasRole('amatai') or currentUser()->hasRole('dirgral') or currentUser()->hasRole('diradmin'))
                            <div class="iuris-student-links">
                                <a href="{{ url('/users/' . $expediente->estudiante->id . '/edit') }}" target="_blank"
                                    rel="noopener noreferrer">
                                    <i class="fa fa-id-card-o" aria-hidden="true"></i>
                                    Cédula: {{ $expediente->estudiante->idnumber }}
                                </a>
                                <a href="{{ url('/notas/ver/estudiante?idnumber=' . $expediente->estudiante->idnumber . '&origen=expedientes&expid=' . $expediente->expid) }}"
                                    target="_blank" rel="noopener noreferrer">
                                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                    Ver notas
                                </a>
                            </div>
                        @endif
                    </div>
                </div>



                <div class="iuris-student-course">
                    <span class="iuris-student-label">Curso</span>
                    @if ($expediente->estudiante->curso->id != 1 and $expediente->estudiante->turno)
                        <span style="padding: 0.2em 0.5em; font-size: 0.9em;"
                            class="badge label {{ $expediente->getColorTurno($expediente->estudiante->turno->color->ref_value) }}">
                            {{ $expediente->estudiante->curso->ref_nombre }}
                            {{ $expediente->getMjs($expediente->estudiante->turno->horario->ref_value) }}
                        </span>
                    @else
                        <span class="badge  label bg-orange">Sin curso asignado</span>
                    @endif
                </div>

                <div style="border-left: 1px solid #ccc; padding-left: 1em; margin-left: 1em;">
                    <span class="iuris-case-summary-label">Fecha de asignación</span>
                    <span class="iuris-case-summary-value">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        {{ getSmallDate($expediente->asignacion->fecha_asig) }}
                        <small class="text-muted">({{ TiempoTrans($expediente->asignacion->fecha_asig) }})</small>
                    </span>
                </div>

            </div>

            <div class="iuris-case-actions">
                @if ($expediente->asignacion)
                    @php
                        $docenteAsignado = $expediente->getDocenteAsig();
                        $puedeEditarCaso = auth()->user()->can('editar_datos_caso');
                        $puedeActivarProceso =
                            $docenteAsignado->idnumber != 'Sin asignar' &&
                            $expediente->asignacion->procesojud_id == 1 &&
                            ($docenteAsignado->idnumber == currentUser()->idnumber ||
                                currentUser()->hasRole('dirgral') ||
                                currentUser()->hasRole('amatai'));
                        $puedeGestionarPausas =
                            $docenteAsignado->idnumber != 'Sin asignar' &&
                            $expediente->exptipoproce_id != 1 &&
                            ($docenteAsignado->idnumber == currentUser()->idnumber ||
                                currentUser()->hasRole('dirgral') ||
                                currentUser()->hasRole('amatai'));
                        $puedeTomarCaso =
                            !$readonly && currentUser()->can('tomar_caso') && $docenteAsignado->name == 'Sin asignar';
                        $periodoVacaciones = $puedeGestionarPausas ? $expediente->validateVacations() : false;
                        $esEstudiante = currentUser()->hasRole('estudiante');
                    @endphp

                    @if ($esEstudiante || $puedeEditarCaso || $puedeActivarProceso || $puedeGestionarPausas || $puedeTomarCaso)
                        <div class="dropdown">
                            <button type="button" class="btn iuris-case-actions-toggle dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                Acciones
                                <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right iuris-case-actions-menu" role="menu"
                                aria-label="Acciones del expediente">
                                @if ($esEstudiante)
                                    <li>
                                        {{-- <a href="#" id="btn_act_pausa_exp" role="menuitem"
                                                >
                                                Ver pausas
                                            </a> --}}

                                        <a href="#" id="btn_act_pausa_exp" role="menuitem">
                                            <i class="fa fa-clock" aria-hidden="true"></i>
                                            Ver pausas
                                        </a>
                                    </li>
                                @endif
                                @if ($puedeEditarCaso)
                                    <li>
                                        <a href="#" id="btnEditar" role="menuitem">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                            Editar datos del caso
                                        </a>
                                        <a href="#" id="btnActualizar" role="menuitem" style="display: none;">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            Guardar cambios
                                        </a>
                                        <a href="#" id="btnCancelar" role="menuitem" style="display: none;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                            Cancelar edición
                                        </a>
                                    </li>
                                @endif

                                @if ($puedeActivarProceso)
                                    <li>
                                        <a href="#" id="btn_act_proc_jur" class="btn_act_proc_jur"
                                            role="menuitem">
                                            <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                            Activar proceso jurídico
                                        </a>
                                    </li>
                                @endif

                                @if ($puedeGestionarPausas)
                                    @if ($periodoVacaciones)
                                        <li class="iuris-case-action-notice" role="presentation">
                                            <span>
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                {!! $periodoVacaciones !!}
                                            </span>
                                        </li>
                                    @else
                                        @if ($expediente->expestado_id == 1 || $expediente->expestado_id == 3)
                                            <li>
                                                <a href="#" id="btn_act_pausa_exp" role="menuitem">
                                                    <i class="fa fa-pause" aria-hidden="true"></i>
                                                    Activar pausa
                                                </a>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="#" id="btn_quit_pausa_exp" role="menuitem">
                                                <i class="fa fa-cog" aria-hidden="true"></i>
                                                Administrar pausas
                                            </a>
                                        </li>
                                    @endif
                                @endif

                                @if ($puedeTomarCaso)
                                    <li>
                                        <a href="#" id="btnTomarCaso" role="menuitem">
                                            <i class="fa fa-check" aria-hidden="true"></i>
                                            Tomar caso
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                @else
                    <span class="text-danger">Error en la asignación</span>
                @endif
            </div>
        </section>
    </div>

</div>

@if ($expediente->exptipoproce_id != 3)
    @php($ramasDerecho = $rama_derecho)
@else
    @php($ramasDerecho = $rama_derecho_defensas)
@endif
<section class="iuris-case-summary" aria-label="Información del expediente">
    <div class="iuris-case-summary-header">
        <div>
            <span class="iuris-case-summary-eyebrow">Resumen</span>
            <h4>Información del expediente</h4>
        </div>
        <i class="fa fa-folder-open-o" aria-hidden="true"></i>
    </div>
    <div class="iuris-case-summary-grid">
        <div class="iuris-case-summary-item">
            <span class="iuris-case-summary-label">Número de expediente</span>
            <span class="iuris-case-summary-value">{{ old('expid', $expediente->expid) }}</span>
            <input type="hidden" name="expid" id="expid" value="{{ old('expid', $expediente->expid) }}">
        </div>
        <div class="iuris-case-summary-item">
            <span class="iuris-case-summary-label">Fecha del expediente</span>
            <span class="iuris-case-summary-value">
                <i class="fa fa-calendar" aria-hidden="true"></i>
                {{ getSmallDate($expediente->expfecha) }}
            </span>
        </div>

        <div class="iuris-case-summary-item">
            <span class="iuris-case-summary-label">Rama del Derecho</span>
            <span class="iuris-case-summary-value iuris-case-editable-value" id="expramaderecho_display">
                {{ $ramasDerecho[$expediente->rama_derecho->id] ?? 'Sin rama asignada' }}
            </span>
            <div class="iuris-case-field-control" style="display: none;">
                <select name="expramaderecho_id" id="expramaderecho_id" class="form-control disabled required"
                    required disabled>
                    <option value="">Selecciona...</option>
                    @foreach ($ramasDerecho as $id => $rama)
                        <option value="{{ $id }}"
                            {{ (string) $expediente->rama_derecho->id === (string) $id ? 'selected' : '' }}>
                            {{ $rama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="iuris-case-summary-item">
            <span class="iuris-case-summary-label">Estado del caso</span>
            <span class="iuris-case-summary-value">
                {{ $estadosPluck[$expediente->expestado_id] ?? 'Sin estado' }}
            </span>
            <select id="expestado_id" class="sr-only" tabindex="-1" aria-hidden="true" disabled>
                @foreach ($estadosPluck as $id => $estado)
                    <option value="{{ $id }}"
                        {{ (string) $expediente->expestado_id === (string) $id ? 'selected' : '' }}>
                        {{ $estado }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="iuris-case-summary-item">
            <span class="iuris-case-summary-label">Tipo de procedimiento</span>
            <span class="iuris-case-summary-value iuris-case-editable-value" id="exptipoproce_display">
                @foreach ($tipo_proceso as $tipo_p)
                    @if ($tipo_p->id == $expediente->exptipoproce_id)
                        {{ $tipo_p->ref_tipproceso }}
                    @endif
                @endforeach
            </span>
            <div class="iuris-case-field-control" style="display: none;">
                <select name="exptipoproce_id" id="exptipoproce_id" class="form-control disabled required" disabled
                    required>
                    @foreach ($tipo_proceso as $tipo_p)
                        <option value="{{ $tipo_p->id }}"
                            {{ $tipo_p->id == $expediente->exptipoproce_id ? 'selected' : '' }}>
                            {{ $tipo_p->ref_tipproceso }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</section>
