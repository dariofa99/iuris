@php
    $docenteAsignado = $expediente->getDocenteAsig();
    $fotoDocente = !empty($docenteAsignado->image) &&
        is_file(public_path('thumbnails/' . $docenteAsignado->image))
            ? asset('thumbnails/' . $docenteAsignado->image)
            : asset('thumbnails/default.jpg');
@endphp

<input type="hidden" value="{{ $docenteAsignado->idnumber }}" name="doc_id_number" id="doc_id_number">
<input type="hidden" value="{{ $docenteAsignado->name }} {{ $docenteAsignado->lastname }}"
    name="doc_full_name" id="doc_full_name">

<section class="iuris-assigned-teacher" aria-label="Docente asesor del expediente">
    <div class="iuris-assigned-teacher-profile">
        <img
            src="{{ $fotoDocente }}"
            class="iuris-assigned-teacher-photo"
            alt="Foto de {{ $docenteAsignado->name }} {{ $docenteAsignado->lastname }}"
        >
        <div class="iuris-assigned-teacher-info">
            <span class="iuris-assigned-teacher-label">Docente asesor</span>
            <strong>{{ $docenteAsignado->name }} {{ $docenteAsignado->lastname }}</strong>
            <span class="iuris-assigned-teacher-status {{ $docenteAsignado->name == 'Sin asignar' ? 'is-pending' : 'is-assigned' }}">
                <i class="fa fa-circle" aria-hidden="true"></i>
                {{ $docenteAsignado->name == 'Sin asignar' ? 'Pendiente de asignación' : 'Asignado al caso' }}
            </span>
        </div>
    </div>

    <div class="iuris-assigned-teacher-actions">
        @if (!$readonly)
            @if (
                currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai') and
                    $docenteAsignado->name != 'Sin asignar')
                <div class="dropdown">
                    <button
                        type="button"
                        class="btn iuris-teacher-actions-toggle dropdown-toggle"
                        id="teacherActionsMenu"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fa fa-sliders" aria-hidden="true"></i>
                        Gestionar docente
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right iuris-teacher-actions-menu"
                        aria-labelledby="teacherActionsMenu">
                        <a href="#" class="dropdown-item btn_change_doc_exp btn-notificar-incidencia"
                            data-lastname="{{ Auth::user()->lastname }}" data-name="{{ Auth::user()->name }}"
                            data-idnumber="{{ Auth::user()->idnumber }}" id="btn_change_doc_exp">
                            <i class="fa fa-exchange" aria-hidden="true"></i>
                            Cambiar docente
                        </a>
                        <a href="#" class="dropdown-item btn_change_doc_exp btn-notificar-incidencia"
                            data-lastname="{{ Auth::user()->lastname }}" data-name="{{ Auth::user()->name }}"
                            data-idnumber="{{ Auth::user()->idnumber }}" id="btn_delete_doc_exp">
                            <i class="fa fa-user-times" aria-hidden="true"></i>
                            Eliminar asignación
                        </a>
                    </div>
                </div>
            @endif

            @if (
                currentUser()->hasRole('coordprac') ||
                    currentUser()->hasRole('diradmin') ||
                    currentUser()->hasRole('dirgral') ||
                    currentUser()->hasRole('amatai') and
                    $docenteAsignado->name != 'Sin asignar')
                <a href="#" class="btn iuris-teacher-history btn_change_doc_exp btn-notificar-incidencia"
                    data-lastname="{{ Auth::user()->lastname }}" data-name="{{ Auth::user()->name }}"
                    data-idnumber="{{ Auth::user()->idnumber }}" id="btn_historial_doc_exp">
                    <i class="fa fa-history" aria-hidden="true"></i>
                    Historial
                </a>
            @endif

            @if (
                $expediente->getAsignacion()->asig_docente !== null and
                    currentUser()->idnumber == $expediente->getAsignacion()->asig_docente->cambio_docidnumber)
                <a href="#" class="btn iuris-teacher-request-action btn_change_doc_exp"
                    id="btn_accept_change_doc_exp">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    Aceptar solicitud
                </a>
            @endif

            @if (
                $docenteAsignado->idnumber == currentUser()->idnumber ||
                    (currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai')))
                @if (
                    $expediente->getAsignacion()->asig_docente !== null and
                        $expediente->getAsignacion()->asig_docente->cambio_docidnumber !== null)
                    <a href="#" class="btn iuris-teacher-request-action btn_change_doc_exp"
                        id="btn_cancel_change_doc_exp">
                        <i class="fa fa-times" aria-hidden="true"></i>
                        Cancelar solicitud
                    </a>
                @elseif(
                    $docenteAsignado->idnumber != 'Sin asignar' and
                        $docenteAsignado->idnumber == currentUser()->idnumber ||
                            (currentUser()->hasRole('diradmin') ||
                                currentUser()->hasRole('dirgral') ||
                                currentUser()->hasRole('amatai')))
                    <a href="#" class="btn iuris-teacher-request-action btn_change_doc_exp btn-notificar-incidencia"
                        id="btn_send_exp_change">
                        <i class="fa fa-random" aria-hidden="true"></i>
                        Solicitar cambio
                    </a>
                @endif
            @endif

            @if (
                $docenteAsignado->name == 'Sin asignar' and
                    currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') || currentUser()->hasRole('amatai'))
                <a href="#" class="btn iuris-teacher-assign btn_change_doc_exp btn-notificar-incidencia"
                    id="btn_asig_exp_doc"
                    data-lastname="{{ Auth::user()->lastname }}" data-name="{{ Auth::user()->name }}"
                    data-idnumber="{{ Auth::user()->idnumber }}">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                    Asignar docente
                </a>
            @endif
        @endif

        @if (
            !$docenteAsignado->hasRole('docente_prueba') and
                currentUser()->hasRole('dirgral') ||
                    currentUser()->hasRole('amatai'))
            <a href="#" class="btn iuris-teacher-disable btn_change_doc_exp btn-notificar-incidencia"
                id="btn_dar_baja_exp"
                data-lastname="{{ Auth::user()->lastname }}" data-name="{{ Auth::user()->name }}"
                data-idnumber="{{ Auth::user()->idnumber }}">
                <i class="fa fa-user-times" aria-hidden="true"></i>
                Dar de baja
            </a>
        @endif

        <a href="#" id="btn_notificar_incidente" class="btn iuris-teacher-report btn-notificar-incidencia"
            title="Reporta incidencias o solicita cambios al administrador">
            <i class="fa fa-bolt" aria-hidden="true"></i>
            Notificar incidencia
        </a>
    </div>
</section>
