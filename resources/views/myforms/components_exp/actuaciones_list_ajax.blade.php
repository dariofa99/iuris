
@foreach ($expediente->getActuaciones($only_estu) as $key => $actuacion)
    @php

        $haycorrecciones = false;
        $actuacion->actestado_id == 102 || $actuacion->actestado_id == 140
            ? ($haycorrecciones = true)
            : ($haycorrecciones = false);
        $hayactuaciones = false;
        $hayhijos = false;
        $ultima_id = '';
        $vencido = false;
        $actFechaLim = $actuacion->fecha_limit;
        // $actFechaLim < date('Y-m-d') ? ($vencido = true) : ($vencido = false);
        if ($actuacion->actestado_id == '102' and $actFechaLim < date('Y-m-d')) {
            $vencido = true;
        }
        if (count($actuacion->getHijos($actuacion)) > 0) {
            $vencido = false;
            foreach ($actuacion->getHijos($actuacion) as $key => $hijo) {
                if ($hijo->actestado_id == '102') {
                    $haycorrecciones = true;
                    $ultima_id = $hijo->id;
                    $actFechaLim = $hijo->fecha_limit;
                    $hayhijos = true;
                }
                if ($actuacion->actestado_id == '102' and $actFechaLim < date('Y-m-d')) {
                    $vencido = true;
                }
                if (
                    $hijo->actestado_id == '101' ||
                    $hijo->actestado_id == '104' ||
                   // $hijo->actestado_id == '234' ||
                    $hijo->actestado_id == '139'
                ) {
                    $haycorrecciones = false;
                    $hayactuaciones = true;
                    $hayhijos = true;
                    //$vencido = false;
                }
            }
        } else {
            /* if ($actuacion->actestado_id == '102' and $actFechaLim < date('Y-m-d')) {
                $vencido = true;
            } */
        }
    @endphp
    <tr style="background-color: rgb(243, 242, 242) !important">
        <td>
            {{ $actuacion->actnombre }}
        </td>
        <td>
            {{ $actuacion->actdescrip }}
        </td>
        <td>
            <span class="badge badge-success" style="background-color: {{ $actuacion->estado->color }} !important">
                {{ $actuacion->estado->ref_nombre }}
            </span>

        </td>
        <td>
            @if ($actuacion->fecha_limit != '' and $hayhijos == false and $actuacion->estado->id != 139)
                {{ getDiffDays(date('Y-m-d'), $actuacion->fecha_limit) }}
                Días
            @else
                {{ getSmallDate($actuacion->actfecha) }}
            @endif

        </td>
        <td>
            <a target="_blank"
                href="{{ url('/actpdfdownload/' . $actuacion->id . '/estudiante') }}">{{ $actuacion->actdocnompropio }}</a>
        </td>
        <td width="14%">
            @if (!$readonly)
                @if ($actuacion->actestado_id != 136 and $actuacion->actestado_id != 138)
                    <button type='button' value="{{ $actuacion->id }}" data-modal="#myModal_act_add_revision"
                        class='btn btn-block btn-default btn-sm buscar_actuacion' style='color:#777'
                        title='Agregar anexo a actuación' data-status='136'>
                        Ag. Anexo
                    </button>
                @endif
                @if (
                    $actuacion->actestado_id == 136 and
                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral'))
                    <button type="button" value="{{ $actuacion->id }}"
                        class="btn btn-default btn-block btn-sm btn_change_status" style="color:#777">
                        Marcar revisado
                    </button>

                    <button type='button' data-estado='235' value="{{ $actuacion->id }}"
                        class='btn btn-danger btn-block btn-sm btn_change_status'>
                        Anular</button>
                @endif

                @if (
                    $actuacion->actestado_id == 235 and
                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('dirgral') ||
                            currentUser()->hasRole('diradmin'))
                    <button type='button' data-estado='136' value="{{ $actuacion->id }}"
                        class='btn btn-danger btn-block btn-sm btn_change_status'>
                        Des-anular</button>
                @endif
                @if (
                    $actuacion->actestado_id == 138 and
                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral'))
                    <button type='button' value="{{ $actuacion->id }}"
                        class='btn btn-default btn-block btn-sm btn_change_status' style='color:#777'>
                        Quitar revisado
                    </button>
                @endif
                @if (
                    $actuacion->actestado_id == 101 and
                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral'))
                    <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $actuacion->id }}"
                        class='btn btn-primary btn-block btn-sm buscar_actuacion'>
                        Revisar
                    </button>
                    <button type='button' value="{{ $actuacion->id }}"
                        class='btn btn-warning btn-block btn-sm cambiar_actuacion_anexo'>
                        Es anexo
                    </button>
                @endif

                @if (
                    $actuacion->actestado_id == 102 and
                        count($actuacion->getHijos($actuacion)) <= 0 || !$hayactuaciones and
                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral'))
                    <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $actuacion->id }}"
                        class='btn btn-warning btn-sm btn-block buscar_actuacion'>
                        Editar Revisón
                    </button>
                @endif
                @if (
                    $actuacion->actestado_id == 101 || $actuacion->actestado_id == 136 || $actuacion->actestado_id == 140 and
                        $actuacion->actusercreated == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral') and
                        $hayactuaciones === false and
                        ($haycorrecciones === false and $hayhijos === false) and
                        $vencido == false)
                    <button data-modal="#myModal_act_edit" type='button' value="{{ $actuacion->id }}"
                        class='btn btn-primary btn-sm buscar_actuacion btn-block'>
                        Editar
                    </button>
                @endif

                @if (
                    $actuacion->actestado_id == 102 || $actuacion->actestado_id == 140 and
                        $haycorrecciones === true and
                        $expediente->expidnumberest == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral'))
                    <button data-modal="#myModal_act_add_revision" type='button' value="{{ $actuacion->id }}"
                        class='btn btn-warning btn-sm btn-block buscar_actuacion' data-titulo_modal='Nueva actuación'>
                        Ag. Corrección </button>
                @endif
                @if (
                    ($actuacion->actestado_id == 101 || $actuacion->actestado_id == 136 || $actuacion->actestado_id == 140 and
                        $actuacion->actusercreated == currentUser()->idnumber ||
                            currentUser()->hasRole('amatai') ||
                            currentUser()->hasRole('diradmin') ||
                            currentUser()->hasRole('dirgral') and
                        $hayactuaciones === false and
                        ($haycorrecciones === false and $hayhijos === false)) ||
                        (currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole('dirgral') and
                            ($haycorrecciones === false and $hayhijos === false)))
                    <button type='button' value="{{ $actuacion->id }}"
                        class='btn btn-danger btn-block btn-sm delete_act'>
                        Eliminar
                    </button>
                @endif
            @endif
            <button data-modal="#myModal_act_details" type='button' value="{{ $actuacion->id }}"
                class='btn btn-success btn-sm buscar_actuacion btn-block'> Detalles
            </button>
        </td>
    </tr>
    @if (count($actuacion->getHijos($actuacion)) > 0)
        @foreach ($actuacion->getHijos($actuacion) as $key => $hijo)
            <tr style="background-color: rgb(232, 237, 237) !important">
                <td>
                    <i class="fa fa-reply" style="transform:rotate(180deg)"> </i>
                    {{ $hijo->actnombre }}
                </td>
                <td>
                    {{ $hijo->actdescrip }}
                </td>
                <td>
                    <span class="badge badge-success" style="background-color: {{ $hijo->estado->color }} !important">
                        {{ $hijo->estado->ref_nombre }}
                    </span>
                </td>
                <td>
                    @if ($hijo->fecha_limit != '' and $hijo->actestado_id == 102 and $ultima_id === $hijo->id and $hayactuaciones === false)
                        {{ getDiffDays(date('Y-m-d'), $hijo->fecha_limit) }} Días
                    @else
                        {{ getSmallDate($hijo->actfecha) }}
                    @endif
                </td>
                <td>
                    <a target="_blank"
                        href="{{ url('/actpdfdownload/' . $hijo->id . '/estudiante') }}">{{ $hijo->actdocnompropio }}</a>
                </td>
                <td>
                    @if (!$readonly)
                        @if (
                            $hijo->actestado_id == 136 and
                                $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                    currentUser()->hasRole('amatai') ||
                                    currentUser()->hasRole('dirgral') ||
                                    currentUser()->hasRole('diradmin'))
                            <button type="button" value="{{ $hijo->id }}"
                                class="btn btn-default btn-block btn-sm btn_change_status" style="color:#777">
                                Marcar revisado
                            </button>

                            <button type='button' data-estado='235' value="{{ $hijo->id }}"
                                class='btn btn-danger btn-block btn-sm btn_change_status'>
                                Anular</button>
                        @endif

                        @if (
                            $hijo->actestado_id == 235 and
                                $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                    currentUser()->hasRole('amatai') ||
                                    currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral'))
                            <button type='button' data-estado='136' value="{{ $hijo->id }}"
                                class='btn btn-danger btn-block btn-sm btn_change_status'>
                                Des-anular</button>
                        @endif
                        @if (
                            $hijo->actestado_id == 138 and
                                $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                    currentUser()->hasRole('amatai') ||
                                    currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral'))
                            <button type='button' value="{{ $hijo->id }}"
                                class='btn btn-default btn-block btn-sm btn_change_status' style='color:#777'>
                                Quitar revisado
                            </button>
                        @endif

                        @if (
                            $hijo->actestado_id == 101 and
                                $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                    currentUser()->hasRole('amatai') ||
                                    currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral'))
                            <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $hijo->id }}"
                                class='btn btn-primary btn-block btn-sm buscar_actuacion'>
                                Revisar
                            </button>

                            <button type='button' value="{{ $hijo->id }}"
                                class='btn btn-warning btn-block btn-sm cambiar_actuacion_anexo'>
                                Es anexo
                            </button>
                        @endif
                        @if (
                            $hijo->actestado_id == 102 and
                                $ultima_id == $hijo->id and
                                $haycorrecciones and
                                $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                    currentUser()->hasRole('amatai') ||
                                    currentUser()->hasRole('diradmin') ||
                                    currentUser()->hasRole('dirgral'))
                            <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $hijo->id }}"
                                class='btn btn-warning btn-sm btn-block buscar_actuacion'>
                                Editar Revisón
                            </button>
                        @endif


                        @if (
                            $hijo->actestado_id == 101 || $hijo->actestado_id == 136 and
                                ($hijo->actusercreated == currentUser()->idnumber and $vencido == false) ||
                                    (currentUser()->hasRole('amatai') ||
                                        currentUser()->hasRole('diradmin') ||
                                        currentUser()->hasRole('dirgral')) ||
                                    ($hijo->actestado_id == 136 and $hijo->actusercreated == currentUser()->idnumber))
                            <button data-modal="#myModal_act_edit" type='button' value="{{ $hijo->id }}"
                                class='btn btn-primary btn-sm buscar_actuacion btn-block'>
                                Editar
                            </button>
                        @endif
                        @if (
                            (($hijo->actestado_id == 101 || $hijo->actestado_id == 136)
                             and
                                ($hijo->actusercreated == currentUser()->idnumber and $vencido == false))
                                 ||
                                    (currentUser()->hasRole('amatai') ||
                                        currentUser()->hasRole('diradmin') ||
                                        currentUser()->hasRole('dirgral')))
                            <button type='button' value="{{ $hijo->id }}"
                                class='btn btn-danger btn-block btn-sm delete_act'>
                                Eliminar
                            </button>
                        @endif
                    @endif
                    <button data-modal="#myModal_act_details" type='button' value="{{ $hijo->id }}"
                        class='btn btn-success btn-sm buscar_actuacion btn-block'>
                        Detalles
                    </button>
                </td>
            </tr>
        @endforeach
    @endif
@endforeach

{{-- 
<div class="container-fluid p-3">
    <div class="row">
        <div class="col-12">
            @foreach ($expediente->getActuaciones($only_estu) as $key => $actuacion)
            @php
                $haycorrecciones = false;
                $actuacion->actestado_id == 102 || $actuacion->actestado_id == 140
                    ? ($haycorrecciones = true)
                    : ($haycorrecciones = false);
                $hayactuaciones = false;
                $hayhijos = false;
                $ultima_id = '';
                $vencido = false;
                $actFechaLim = $actuacion->fecha_limit;
                
                if ($actuacion->actestado_id == '102' and $actFechaLim < date('Y-m-d')) {
                    $vencido = true;
                }
                
                if (count($actuacion->getHijos($actuacion)) > 0) {
                    $vencido = false;
                    foreach ($actuacion->getHijos($actuacion) as $key => $hijo) {
                        if ($hijo->actestado_id == '102') {
                            $haycorrecciones = true;
                            $ultima_id = $hijo->id;
                            $actFechaLim = $hijo->fecha_limit;
                            $hayhijos = true;
                        }
                        if ($actuacion->actestado_id == '102' and $actFechaLim < date('Y-m-d')) {
                            $vencido = true;
                        }
                        if (
                            $hijo->actestado_id == '101' ||
                            $hijo->actestado_id == '104' ||
                            $hijo->actestado_id == '139'
                        ) {
                            $haycorrecciones = false;
                            $hayactuaciones = true;
                            $hayhijos = true;
                        }
                    }
                }
                
                // Determinar color y estilo según estado
                $estadoColor = $actuacion->estado->color ?? '#6c757d';
                $estadoNombre = $actuacion->estado->ref_nombre ?? 'Desconocido';
                
                // Badge de vencido
                $vencidoBadge = $vencido ? '<span class="badge badge-danger ml-2"><i class="fas fa-exclamation-circle mr-1"></i>Vencido</span>' : '';
            @endphp

            <!-- Tarjeta principal de actuación -->
            <div class="card shadow-lg border-0 mb-4 animated fadeInUp" style="animation-delay: {{ $key * 0.1 }}s;">
                <!-- Cabecera con gradiente -->
                <div class="card-header border-0 p-0 overflow-hidden rounded-top" 
                     style="background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);">
                    <div class="d-flex align-items-center p-3">
                        <div class="mr-3">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                 style="width: 45px; height: 45px;">
                                <i class="fas fa-gavel text-primary" style="font-size: 1.2rem;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h5 class="text-white mb-1 font-weight-bold">
                                        {{ $actuacion->actnombre }}
                                        {!! $vencidoBadge !!}
                                    </h5>
                                    <div class="d-flex flex-wrap align-items-center">
                                        <span class="badge badge-light mr-2 mb-1 px-3 py-2">
                                            <i class="fas fa-hashtag mr-1"></i>
                                            ID: {{ $actuacion->id }}
                                        </span>
                                        <span class="badge mr-2 mb-1 px-3 py-2" 
                                              style="background-color: {{ $estadoColor }}20; color: {{ $estadoColor }}; border: 1px solid {{ $estadoColor }};">
                                            <i class="fas fa-circle mr-1" style="color: {{ $estadoColor }};"></i>
                                            {{ $estadoNombre }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 mt-sm-0">
                                    <button data-modal="#myModal_act_details" type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-light btn-sm buscar_actuacion'>
                                        <i class="fas fa-info-circle mr-1"></i> Detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cuerpo de la tarjeta -->
                <div class="card-body p-0">
                    <!-- Información principal -->
                    <div class="p-4 border-bottom">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex mb-3">
                                    <div class="mr-3">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 35px; height: 35px;">
                                            <i class="fas fa-align-left text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted text-uppercase font-weight-bold">Descripción</small>
                                        <p class="mb-0 text-dark">{{ $actuacion->actdescrip ?: 'Sin descripción' }}</p>
                                    </div>
                                </div>
                                
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 35px; height: 35px;">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted text-uppercase font-weight-bold">Documento</small>
                                        <div>
                                            @if($actuacion->actdocnompropio)
                                                <a target="_blank" href="{{ url('/actpdfdownload/' . $actuacion->id . '/estudiante') }}" 
                                                   class="text-primary font-weight-bold d-flex align-items-center">
                                                    <i class="fas fa-download mr-1"></i>
                                                    {{ $actuacion->actdocnompropio }}
                                                </a>
                                            @else
                                                <span class="text-muted">Sin documento adjunto</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded-lg">
                                    <small class="text-muted text-uppercase font-weight-bold d-block mb-2">
                                        <i class="far fa-calendar-alt mr-1"></i> Información de plazo
                                    </small>
                                    @if ($actuacion->fecha_limit != '' and $hayhijos == false and $actuacion->estado->id != 139)
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <span class="badge badge-{{ $vencido ? 'danger' : 'info' }} badge-pill px-3 py-2" 
                                                      style="font-size: 1rem;">
                                                    <i class="fas {{ $vencido ? 'fa-clock' : 'fa-hourglass-half' }} mr-1"></i>
                                                    {{ getDiffDays(date('Y-m-d'), $actuacion->fecha_limit) }} Días
                                                </span>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-flag-checkered mr-1"></i>
                                                {{ getSmallDate($actuacion->fecha_limit) }}
                                            </small>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            <span>{{ getSmallDate($actuacion->actfecha) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    @if (!$readonly)
                    <div class="px-4 py-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <small class="text-muted text-uppercase font-weight-bold mr-3">
                                    <i class="fas fa-tools mr-1"></i> Acciones disponibles:
                                </small>
                            </div>
                            <div class="btn-group flex-wrap" role="group">
                                @if ($actuacion->actestado_id != 136 and $actuacion->actestado_id != 138)
                                    <button type='button' value="{{ $actuacion->id }}" data-modal="#myModal_act_add_revision"
                                            class='btn btn-outline-primary btn-sm buscar_actuacion' 
                                            title='Agregar anexo a actuación' data-status='136'>
                                        <i class="fas fa-paperclip mr-1"></i> Ag. Anexo
                                    </button>
                                @endif

                                @if (
                                    $actuacion->actestado_id == 136 and
                                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                            currentUser()->hasRole('amatai') ||
                                            currentUser()->hasRole('diradmin') ||
                                            currentUser()->hasRole('dirgral'))
                                    <button type="button" value="{{ $actuacion->id }}"
                                            class="btn btn-outline-success btn-sm btn_change_status">
                                        <i class="fas fa-check-circle mr-1"></i> Marcar revisado
                                    </button>
                                    <button type='button' data-estado='235' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-danger btn-sm btn_change_status'>
                                        <i class="fas fa-ban mr-1"></i> Anular
                                    </button>
                                @endif

                                @if (
                                    $actuacion->actestado_id == 235 and
                                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                            currentUser()->hasRole('amatai') ||
                                            currentUser()->hasRole('dirgral') ||
                                            currentUser()->hasRole('diradmin'))
                                    <button type='button' data-estado='136' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-warning btn-sm btn_change_status'>
                                        <i class="fas fa-undo-alt mr-1"></i> Des-anular
                                    </button>
                                @endif

                                @if (
                                    $actuacion->actestado_id == 138 and
                                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                            currentUser()->hasRole('amatai') ||
                                            currentUser()->hasRole('diradmin') ||
                                            currentUser()->hasRole('dirgral'))
                                    <button type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-secondary btn-sm btn_change_status'>
                                        <i class="fas fa-times-circle mr-1"></i> Quitar revisado
                                    </button>
                                @endif

                                @if (
                                    $actuacion->actestado_id == 101 and
                                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                            currentUser()->hasRole('amatai') ||
                                            currentUser()->hasRole('diradmin') ||
                                            currentUser()->hasRole('dirgral'))
                                    <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-primary btn-sm buscar_actuacion'>
                                        <i class="fas fa-check-double mr-1"></i> Revisar
                                    </button>
                                    <button type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-warning btn-sm cambiar_actuacion_anexo'>
                                        <i class="fas fa-link mr-1"></i> Es anexo
                                    </button>
                                @endif

                                @if (
                                    $actuacion->actestado_id == 102 and
                                        count($actuacion->getHijos($actuacion)) <= 0 || !$hayactuaciones and
                                        $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                            currentUser()->hasRole('amatai') ||
                                            currentUser()->hasRole('diradmin') ||
                                            currentUser()->hasRole('dirgral'))
                                    <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-warning btn-sm buscar_actuacion'>
                                        <i class="fas fa-edit mr-1"></i> Editar Revisión
                                    </button>
                                @endif

                                @if (
                                    ($actuacion->actestado_id == 101 || $actuacion->actestado_id == 136 || $actuacion->actestado_id == 140) &&
                                    (($actuacion->actusercreated == currentUser()->idnumber || 
                                      currentUser()->hasRole('amatai') || 
                                      currentUser()->hasRole('diradmin') || 
                                      currentUser()->hasRole('dirgral')) &&
                                      $hayactuaciones === false &&
                                      $haycorrecciones === false && 
                                      $hayhijos === false && 
                                      $vencido == false))
                                    <button data-modal="#myModal_act_edit" type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-primary btn-sm buscar_actuacion'>
                                        <i class="fas fa-pen mr-1"></i> Editar
                                    </button>
                                @endif

                                @if (
                                    ($actuacion->actestado_id == 102 || $actuacion->actestado_id == 140) &&
                                    $haycorrecciones === true &&
                                    ($expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                     currentUser()->hasRole('amatai') ||
                                     currentUser()->hasRole('diradmin') ||
                                     currentUser()->hasRole('dirgral')))
                                    <button data-modal="#myModal_act_add_revision" type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-warning btn-sm buscar_actuacion' data-titulo_modal='Nueva actuación'>
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Ag. Corrección
                                    </button>
                                @endif

                                @if (
                                    (($actuacion->actestado_id == 101 || $actuacion->actestado_id == 136 || $actuacion->actestado_id == 140) &&
                                     ($actuacion->actusercreated == currentUser()->idnumber ||
                                      currentUser()->hasRole('amatai') ||
                                      currentUser()->hasRole('diradmin') ||
                                      currentUser()->hasRole('dirgral')) &&
                                     $hayactuaciones === false &&
                                     $haycorrecciones === false && 
                                     $hayhijos === false) ||
                                    (currentUser()->hasRole('amatai') || 
                                     currentUser()->hasRole('diradmin') || 
                                     currentUser()->hasRole('dirgral')) &&
                                     $haycorrecciones === false && 
                                     $hayhijos === false)
                                    <button type='button' value="{{ $actuacion->id }}"
                                            class='btn btn-outline-danger btn-sm delete_act'>
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Hijos (actuaciones hijas) -->
            @if (count($actuacion->getHijos($actuacion)) > 0)
                @foreach ($actuacion->getHijos($actuacion) as $key => $hijo)
                    @php
                        $hijoVencido = false;
                        if ($hijo->actestado_id == '102' && $hijo->fecha_limit < date('Y-m-d')) {
                            $hijoVencido = true;
                        }
                        
                        $hijoEstadoColor = $hijo->estado->color ?? '#6c757d';
                        $hijoEstadoNombre = $hijo->estado->ref_nombre ?? 'Desconocido';
                        $hijoVencidoBadge = $hijoVencido ? '<span class="badge badge-danger ml-2"><i class="fas fa-exclamation-circle mr-1"></i>Vencido</span>' : '';
                    @endphp

                    <!-- Tarjeta hija con indentación visual -->
                    <div class="card border-0 shadow-sm mb-4 ml-4 animated fadeInRight" style="animation-delay: {{ $key * 0.1 }}s; border-left: 4px solid #3498db !important;">
                        <div class="card-header bg-light border-0 p-3">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 35px; height: 35px;">
                                        <i class="fas fa-code-branch"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">
                                                <i class="fa fa-reply mr-2" style="transform:rotate(180deg); color: #3498db;"></i>
                                                {{ $hijo->actnombre }}
                                                {!! $hijoVencidoBadge !!}
                                            </h6>
                                            <div class="d-flex flex-wrap align-items-center">
                                                <span class="badge badge-light mr-2 mb-1 px-3 py-2">
                                                    <i class="fas fa-hashtag mr-1"></i>
                                                    ID: {{ $hijo->id }}
                                                </span>
                                                <span class="badge mr-2 mb-1 px-3 py-2" 
                                                      style="background-color: {{ $hijoEstadoColor }}20; color: {{ $hijoEstadoColor }}; border: 1px solid {{ $hijoEstadoColor }};">
                                                    <i class="fas fa-circle mr-1" style="color: {{ $hijoEstadoColor }};"></i>
                                                    {{ $hijoEstadoNombre }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex mb-2">
                                        <div class="mr-3">
                                            <i class="fas fa-align-left text-secondary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted">Descripción:</small>
                                            <p class="mb-1">{{ $hijo->actdescrip ?: 'Sin descripción' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <i class="fas fa-file-pdf text-danger"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted">Documento:</small>
                                            <div>
                                                @if($hijo->actdocnompropio)
                                                    <a target="_blank" href="{{ url('/actpdfdownload/' . $hijo->id . '/estudiante') }}" 
                                                       class="text-primary">
                                                        <i class="fas fa-download mr-1"></i>
                                                        {{ $hijo->actdocnompropio }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Sin documento</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="bg-light p-2 rounded">
                                        <small class="text-muted d-block mb-1">
                                            <i class="far fa-calendar-alt mr-1"></i> Fecha:
                                        </small>
                                        @if ($hijo->fecha_limit != '' and $hijo->actestado_id == 102 and $ultima_id === $hijo->id and $hayactuaciones === false)
                                            <span class="badge badge-{{ $hijoVencido ? 'danger' : 'info' }} badge-pill px-3 py-2">
                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                {{ getDiffDays(date('Y-m-d'), $hijo->fecha_limit) }} Días
                                            </span>
                                            <small class="d-block text-muted mt-1">
                                                Límite: {{ getSmallDate($hijo->fecha_limit) }}
                                            </small>
                                        @else
                                            <span class="font-weight-bold">{{ getSmallDate($hijo->actfecha) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (!$readonly)
                        <div class="card-footer bg-white border-0 p-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <small class="text-muted">
                                    <i class="fas fa-tools mr-1"></i> Acciones:
                                </small>
                                <div class="btn-group flex-wrap" role="group">
                                    @if (
                                        $hijo->actestado_id == 136 and
                                            $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                                currentUser()->hasRole('amatai') ||
                                                currentUser()->hasRole('dirgral') ||
                                                currentUser()->hasRole('diradmin'))
                                        <button type="button" value="{{ $hijo->id }}"
                                                class="btn btn-outline-success btn-sm btn_change_status">
                                            <i class="fas fa-check-circle mr-1"></i> Marcar revisado
                                        </button>
                                        <button type='button' data-estado='235' value="{{ $hijo->id }}"
                                                class='btn btn-outline-danger btn-sm btn_change_status'>
                                            <i class="fas fa-ban mr-1"></i> Anular
                                        </button>
                                    @endif

                                    @if (
                                        $hijo->actestado_id == 235 and
                                            $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                                currentUser()->hasRole('amatai') ||
                                                currentUser()->hasRole('diradmin') ||
                                                currentUser()->hasRole('dirgral'))
                                        <button type='button' data-estado='136' value="{{ $hijo->id }}"
                                                class='btn btn-outline-warning btn-sm btn_change_status'>
                                            <i class="fas fa-undo-alt mr-1"></i> Des-anular
                                        </button>
                                    @endif

                                    @if (
                                        $hijo->actestado_id == 138 and
                                            $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                                currentUser()->hasRole('amatai') ||
                                                currentUser()->hasRole('diradmin') ||
                                                currentUser()->hasRole('dirgral'))
                                        <button type='button' value="{{ $hijo->id }}"
                                                class='btn btn-outline-secondary btn-sm btn_change_status'>
                                            <i class="fas fa-times-circle mr-1"></i> Quitar revisado
                                        </button>
                                    @endif

                                    @if (
                                        $hijo->actestado_id == 101 and
                                            $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                                currentUser()->hasRole('amatai') ||
                                                currentUser()->hasRole('diradmin') ||
                                                currentUser()->hasRole('dirgral'))
                                        <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $hijo->id }}"
                                                class='btn btn-outline-primary btn-sm buscar_actuacion'>
                                            <i class="fas fa-check-double mr-1"></i> Revisar
                                        </button>
                                        <button type='button' value="{{ $hijo->id }}"
                                                class='btn btn-outline-warning btn-sm cambiar_actuacion_anexo'>
                                            <i class="fas fa-link mr-1"></i> Es anexo
                                        </button>
                                    @endif

                                    @if (
                                        $hijo->actestado_id == 102 and
                                            $ultima_id == $hijo->id and
                                            $haycorrecciones and
                                            $expediente->getDocenteAsig()->idnumber == currentUser()->idnumber ||
                                                currentUser()->hasRole('amatai') ||
                                                currentUser()->hasRole('diradmin') ||
                                                currentUser()->hasRole('dirgral'))
                                        <button data-modal="#myModal_act_edit_docen" type='button' value="{{ $hijo->id }}"
                                                class='btn btn-outline-warning btn-sm buscar_actuacion'>
                                            <i class="fas fa-edit mr-1"></i> Editar Revisión
                                        </button>
                                    @endif

                                    @if (
                                        ($hijo->actestado_id == 101 || $hijo->actestado_id == 136) &&
                                        (($hijo->actusercreated == currentUser()->idnumber && $vencido == false) ||
                                         currentUser()->hasRole('amatai') ||
                                         currentUser()->hasRole('diradmin') ||
                                         currentUser()->hasRole('dirgral')))
                                        <button data-modal="#myModal_act_edit" type='button' value="{{ $hijo->id }}"
                                                class='btn btn-outline-primary btn-sm buscar_actuacion'>
                                            <i class="fas fa-pen mr-1"></i> Editar
                                        </button>
                                    @endif

                                    @if (
                                        (($hijo->actestado_id == 101 || $hijo->actestado_id == 136) &&
                                         ($hijo->actusercreated == currentUser()->idnumber && $vencido == false)) ||
                                         currentUser()->hasRole('amatai') ||
                                         currentUser()->hasRole('diradmin') ||
                                         currentUser()->hasRole('dirgral'))
                                        <button type='button' value="{{ $hijo->id }}"
                                                class='btn btn-outline-danger btn-sm delete_act'>
                                            <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                        </button>
                                    @endif
                                </div>
                                <button data-modal="#myModal_act_details" type='button' value="{{ $hijo->id }}"
                                        class='btn btn-outline-success btn-sm buscar_actuacion ml-2'>
                                    <i class="fas fa-info-circle mr-1"></i> Detalles
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                @endforeach
            @endif
            @endforeach
        </div>
    </div>
</div>


 --}}