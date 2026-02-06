@extends('layouts.dashboard')

@section('titulo_general')
    Notas

@endsection

@section('titulo_area')
    <h3>
        Administración de notas
    </h3>
@endsection
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <style>
        /* círculo moderno */
        .check-ui {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid #dee2e6;
            background: #fff;
            cursor: pointer;
            transition: all .25s ease;
            display: inline-block;
            position: relative;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .12);
        }

        /* hover */
        .check-ui:hover {
            transform: scale(1.08);
        }

        /* checked */
        .check-modern:checked+.check-ui {
            background: #207e1c;
            border-color: #207e1c;
        }

        /* icono check */
        .check-modern:checked+.check-ui::after {
            content: "✓";
            color: white;
            font-weight: bold;
            font-size: 15px;
            position: absolute;
            top: 1px;
            left: 6px;
        }

        /* disabled */
        .check-modern:disabled+.check-ui {
            opacity: .4;
            cursor: not-allowed;
        }
  
        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated {
            animation-duration: 0.6s;
            animation-fill-mode: both;
        }

        .fadeInUp {
            animation-name: fadeInUp;
        }

        /* Efectos hover */
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .hover-glow:hover {
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .btn-pulse:hover {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        /* Iconos y círculos */
        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 1.8rem;
        }

        .icon-wrapper .icon-circle.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .icon-wrapper .icon-circle.bg-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .icon-wrapper .icon-circle.bg-info {
            background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
        }

        .icon-wrapper .icon-circle.bg-warning {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
        }

        /* Scroll personalizado */
        .nota-contenido::-webkit-scrollbar {
            width: 6px;
        }

        .nota-contenido::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .nota-contenido::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .nota-contenido::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Texto con saltos de línea */
        .text-break {
            word-break: break-word;
            white-space: pre-wrap;
        }

        /* Badges mejorados */
        .badge-pill {
            border-radius: 50rem;
        }

        /* Acordeón mejorado */
        .collapse.show .toggle-icon {
            transform: rotate(90deg);
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        /* Tarjetas de estadísticas */
        .stats-card {
            border-top: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            border-top-color: var(--primary-color, #007bff);
            transform: translateY(-5px);
        }

        /* Modal moderno */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            border-radius: 15px 15px 0 0;
        }

        /* Estado vacío */
        .empty-state {
            padding: 3rem 0;
        }

        .dotted-line {
            border-top: 2px dashed #dee2e6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .card-header .btn {
                margin-top: 10px;
                align-self: flex-end;
            }

            .col-md-6.col-lg-4 {
                width: 100%;
            }
        }

        .card {
            border-radius: 14px;
        }

        .form-control-lg {
            height: 45px;
            font-size: 14px;
        }

        .card:hover {
            transform: translateY(-2px);
            transition: .2s;
        }

        /* Separadores de bloques */
        .separador-fecha {
            border-left: 4px solid #2575fc;
            box-shadow: 0 2px 10px rgba(37, 117, 252, 0.1);
        }

        .separador-bloque {
            position: relative;
        }

        .separador-bloque:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #dee2e6, transparent);
            z-index: 0;
        }

        /* Indicador de tipo de nota */
        .card-type-indicator {
            border-radius: 8px 8px 0 0;
        }

        .nota-texto {
            border-top: 3px solid #17a2b8;
        }

        .nota-numerica {
            border-top: 3px solid #ffc107;
        }

        /* Estilos específicos para notas numéricas */
        .display-4 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        /* Responsive para separadores */
        @media (max-width: 768px) {
            .separador-fecha .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .separador-fecha .ml-auto {
                margin-left: 0 !important;
                margin-top: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('area_forms')

    @include('msg.alerts')

    <div class="row">


        <!-- FORM CARD -->
        <div class="col-md-9">

            <div class="shadow-lg border-0 rounded-lg">
                @if (currentUser()->hasRole('estudiante'))

                    <div class="card shadow-lg border-0 rounded-lg animated fadeInUp">
                        <div class="card-body p-3">
                            <div class="row align-items-center no-gutters">
                                <!-- Imagen de Perfil -->
                                <div class="col-auto mr-3">
                                    @if ($user->image)
                                        <img src="{{ is_file(public_path('thumbnails/' . $user->image)) ? asset('thumbnails/' . $user->image) : asset('thumbnails/default.jpg') }}"
                                            alt="{{ $user->name }}" class="rounded-circle shadow"
                                            style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #28a745;">
                                    @else
                                        <div class="rounded-circle shadow d-inline-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 2px solid #667eea;">
                                            <i class="fas fa-user text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Información del Estudiante -->
                                <div class="col">
                                    <div class="mb-1">
                                        <small class="text-muted d-block">
                                            Estudiante
                                        </small>
                                    </div>
                                    <div class="mb-1">
                                        <h5 class="font-weight-bold text-dark mb-0">
                                            {{ $user->name }} {{ $user->lastname }}
                                        </h5>
                                        <div>
                                            @if ($user->email)
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                                                </small>
                                            @endif
                                            @if ($user->tel1)
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-phone mr-1"></i>{{ $user->tel1 }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <form action="/notas/ver/estudiante" method="GET" id="myFormBuscarNotas">

                                {{-- FILA PRINCIPAL --}}
                                <div class="form-row align-items-end">

                                    {{-- Documento --}}
                                    @if (auth()->user()->can('ver_notas_estudiante'))
                                        <div class="col-md-3">
                                            <label class="small text-muted mb-1">Documento</label>
                                            <input placeholder="Ingrese un número de documento" required type="text"
                                                value="{{ Request::get('idnumber') }}" class="form-control shadow-sm"
                                                name="idnumber">
                                        </div>
                                    @else
                                        @if (Request::has('idnumber'))
                                            <input required type="hidden" value="{{ Request::get('idnumber') }}"
                                                name="idnumber">
                                        @endif
                                    @endif


                                    {{-- ORIGEN (MISMO FOREACH) --}}
                                    <div class="col-md-2">
                                        <label class="small text-muted mb-1">Origen</label>
                                        <select class="form-control shadow-sm" name="origen">

                                            <option @if (Request::has('origen') and Request::get('origen') == 'expedientes') selected @endif value="expedientes">
                                                Expedientes
                                            </option>

                                            <option @if (Request::has('origen') and Request::get('origen') == 'conciliaciones') selected @endif
                                                value="conciliaciones">
                                                Conciliaciones
                                            </option>

                                        </select>
                                    </div>


                                    {{-- PERIODO (TU FOREACH ORIGINAL) --}}
                                    <div class="col-md-3">
                                        <label class="small text-muted mb-1">Periodo</label>
                                        <select class="form-control shadow-sm" name="perid">

                                            @foreach ($periodos as $key => $periodo)
                                                <option @if (Request::has('perid') and Request::get('perid') == $periodo->id) selected @endif
                                                    value="{{ $periodo->id }}">
                                                    {{ $periodo->prddes_periodo }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>


                                    {{-- CORTE (TU FOREACH ORIGINAL) --}}
                                    <div class="col-md-2">
                                        <label class="small text-muted mb-1">Corte</label>
                                        <select class="form-control shadow-sm" name="segid">

                                            @foreach ($segmentos as $key => $segmento)
                                                <option @if (
                                                    (Request::has('segid') and Request::get('segid') == $segmento->id) ||
                                                        ($segmentoAct->id == $segmento->id and !Request::has('segid'))) selected @endif
                                                    value="{{ $segmento->id }}">
                                                    {{ $segmento->segnombre }}
                                                </option>
                                            @endforeach

                                            <option value="">Ver todos</option>

                                        </select>
                                    </div>


                                    {{-- BOTÓN --}}
                                    <div class="col-md-2">
                                        <button class="btn btn-primary btn-block shadow-sm">
                                            🔍 Buscar
                                        </button>
                                    </div>

                                    @isset($user)
                                        <div class="col-md-12 mt-3">
                                            <div class="mb-1">
                                                <h5 class="font-weight-bold text-dark mb-0">
                                                    {{ $user->name }} {{ $user->lastname }}
                                                </h5>
                                                <small class="text-muted d-block">
                                                    @if ($user->email)
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                                                        </small>
                                                    @endif
                                                    @if ($user->tel1)
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-phone mr-1"></i>{{ $user->tel1 }}
                                                        </small>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    @endisset
                                </div>

                            </form>
                        </div>
                    </div>

                @endif

            </div>

        </div>


        @php
            $nota = $asistencia->nota_proporcional ?? 0;
            $nota = is_numeric($nota) ? floatval($nota) : 0;

            $color = $nota >= 4 ? 'success' : ($nota >= 3 ? 'warning' : 'danger');

        @endphp
        {{--   <div class="col-md-3">
            <div class="card shadow-lg border-0 rounded-4 p-4 text-center">

                <h6 class="text-muted">Nota de Asistencia</h6>

                <h1 class="display-4 fw-bold text-{{ $color }}">
                    {{ $nota ? number_format($nota, 2) : 'N/A' }}
                </h1>

                <div class="progress mt-3" style="height:10px;">
                    <div class="progress-bar bg-{{ $color }}" style="width: {{ ($nota / 5) * 100 }}%">
                    </div>
                </div>

                
            </div>
        </div> --}}
    </div>






    <!-- Incluir Font Awesome -->


    <div class="container-fluid p-3">
        <!-- Estadísticas rápidas si hay datos -->
        @if (count($notas) > 0)
            {{--   <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-info text-white shadow-lg border-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-1"><i class="fas fa-chart-bar mr-2"></i> Resumen de Notas</h4>
                                    <p class="mb-0 opacity-75">Total de expedientes: {{ count($notas) }}</p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-light btn-sm" type="button" data-toggle="collapse"
                                        data-target="#statsCollapse">
                                        <i class="fas fa-chevron-down"></i> Ver estadísticas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endif

        <div class="row">
            <div class="col-12">
                @php
                    $count = 1;
                    $promedio_c = 0;
                    $promedio_a = 0;
                    $promedio_e = 0;
                    $contador_c = 0;
                    $contador_a = 0;
                    $contador_e = 0;
                @endphp

                @forelse($notas as $key => $data)
                    @php
                        // Procesar datos para promedios
                        foreach ($data as $nota) {
                            if ($nota['concepto_nota_id'] == '1' and is_numeric($nota['nota'])) {
                                $promedio_c = $promedio_c + $nota['nota'];
                                $contador_c = $contador_c + 1;
                            }
                            if ($nota['concepto_nota_id'] == '2' and is_numeric($nota['nota'])) {
                                $promedio_a = $promedio_a + $nota['nota'];
                                $contador_a = $contador_a + 1;
                            }
                            if ($nota['concepto_nota_id'] == '3' and is_numeric($nota['nota'])) {
                                $promedio_e = $promedio_e + $nota['nota'];
                                $contador_e = $contador_e + 1;
                            }
                        }
                    @endphp


                    <!-- Tarjeta principal para cada expediente -->
                    <div class="card shadow-lg border-0 mb-4 animated fadeInUp"
                        style="animation-delay: {{ $count * 0.1 }}s;">
                        <!-- Cabecera con gradiente -->
                        <div class="card-header border-0 p-0 overflow-hidden"
                            style="background: linear-gradient(135deg, #177839 90%, #ffd13b 100%);">
                            <div class="d-flex align-items-center p-4">
                                <div class="mr-4">
                                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                        style="width: 50px; height: 50px;">
                                        <span class="text-primary font-weight-bold"
                                            style="font-size: 1.2rem;">{{ $count }}</span>
                                        @php $count = $count + 1; @endphp
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="row align-items-center no-gutters w-100">
                                        <!-- Expediente Número -->
                                        <div class="col-auto pr-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <small class="text-white opacity-75 mb-1">
                                                    <i class="fas fa-folder-open mr-1"></i>Expediente
                                                </small>
                                                <a style="color: #ffffff !important; text-decoration: none;" target="_blank"
                                                    href="/expedientes/{{ $data[0]['expediente'] }}/edit"
                                                    class="h4 font-weight-bold mb-0 hover-glow" style="font-size: 1.8rem;">
                                                    {{ $data[0]['expediente'] }}
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Separador visual -->
                                        <div
                                            style="height: 50px; width: 2px; background: rgba(255,255,255,0.3); margin: 0 20px;">
                                        </div>

                                        <!-- Información del expediente -->
                                        <div class="col">
                                            <div class="row no-gutters">
                                                <!-- Período -->
                                                <div class="col-auto mr-4">
                                                    <div class="text-white">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-calendar fa-lg mr-2"
                                                                style="opacity: 0.8;"></i>
                                                            <div>
                                                                {{-- <small class="opacity-75 d-block">Período</small> --}}
                                                                <strong>{{ $data[0]['periodo'] }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Corte/Segmento -->
                                                {{--   <div class="col-auto mr-4">
                                                    <div class="text-white">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-cut fa-lg mr-2" style="opacity: 0.8;"></i>
                                                            <div>
                                                                 <small class="opacity-75 d-block">Corte</small>
                                                                <strong>{{ $data[0]['segmento'] }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}

                                                <!-- Cantidad de notas -->
                                                <div class="col-auto">
                                                    <div class="text-white">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-sticky-note fa-lg mr-2"
                                                                style="opacity: 0.8;"></i>
                                                            <div>
                                                                <small class="opacity-75 d-block">Notas
                                                                    <strong>{{ count($data) }} registros</strong></small>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Cuerpo de la tarjeta -->
                        <div class="card-body p-0">
                            <!-- Botón para expandir notas -->
                            <div class="p-3 border-bottom">
                                <button class="btn btn-link btn-block text-dark text-left p-0" type="button"
                                    data-toggle="collapse" data-target="#notasCollapse{{ $count }}"
                                    aria-expanded="false">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-chevron-down mr-2 toggle-icon"></i>
                                            <span class="font-weight-bold h6 mb-0">Notas</span>
                                            <small class="text-muted ml-3">({{ count($data) }} registros)</small>
                                        </div>
                                        <span class="badge badge-primary badge-pill px-3 py-2">
                                            <i class="fas fa-eye mr-1"></i> Expandir
                                        </span>
                                    </div>
                                </button>
                            </div>

                            <!-- Contenido colapsable de notas -->
                            <!-- Contenido colapsable de notas -->
                            <div id="notasCollapse{{ $count }}" class="collapse show">
                                <div class="p-3 bg-light">
                                    @php
                                        ksort($data);

                                        $notasAgrupadas = [];
                                        $grupoActual = [];

                                        foreach ($data as $nota) {
                                            $grupoActual[] = $nota;

                                            // si es texto → cerrar bloque
                                            if (!is_numeric($nota['nota'])) {
                                                $notasAgrupadas[] = $grupoActual;
                                                $grupoActual = [];
                                            }
                                        }

                                        // por si quedó algo sin cerrar
                                        if (!empty($grupoActual)) {
                                            $notasAgrupadas[] = $grupoActual;
                                        }
                                        // dd($data, $notasAgrupadas );
                                        //invertir el orden para mostrar las notas más recientes primero
                                        foreach ($notasAgrupadas as &$grupo) {
                                            $texto = null;
                                            $numericas = [];

                                            foreach ($grupo as $nota) {
                                                if ($nota['concepto_nota_id'] == 4) {
                                                    $texto = $nota;
                                                } else {
                                                    $numericas[] = $nota;
                                                }
                                            }

                                            $grupo = $texto ? array_merge([$texto], $numericas) : $numericas;
                                        }
                                        unset($grupo);

                                    @endphp

                                    @foreach ($notasAgrupadas as $key_nota => $notasDelDia)
                                        <!-- Separador de bloque por fecha -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-grow-1">
                                                        <div class="separador-fecha bg-white shadow-sm p-3 rounded-lg">
                                                            <div class="d-flex align-items-center">
                                                                <div class="mr-3">
                                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                                        style="width: 40px; height: 40px;">
                                                                        <i class="fas fa-calendar-day"></i>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <h6 class="font-weight-bold text-primary mb-0">
                                                                        Bloque de Notas
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        <i class="far fa-clock mr-1"></i>
                                                                        {{ count($notasDelDia) }} notas en este bloque
                                                                    </small>
                                                                </div>
                                                                <div class="ml-auto">
                                                                    @if (currentUser()->hasRole('amatai') || currentUser()->can('eliminar_notas'))
                                                                        <div>
                                                                            <button
                                                                                id="btn_eliminar_notas_ver-{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }}"
                                                                                class="btn btn-danger btn-outline-light btn-sm btn_eliminar_notas_ver btn-pulse"
                                                                                data-id="{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }}">
                                                                                <i class="fas fa-trash-alt mr-1"></i>
                                                                                Eliminar
                                                                            </button>
                                                                            <button style="display: none"
                                                                                id="btn_cancel_notas_ver-{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }}"
                                                                                class="btn btn-warning btn-outline-light btn-sm btn_cancel_notas_ver btn-pulse"
                                                                                data-id="{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }}">
                                                                                <i class="fas fa-times mr-1"></i>
                                                                                Cancelar
                                                                            </button>
                                                                            <button style="display: none"
                                                                                id="btn_delete_notas_ver-{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }}"
                                                                                class="btn btn-success btn-outline-light btn-sm btn_delete_notas_ver btn-pulse"
                                                                                data-id="{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }}">
                                                                                <i class="fas fa-check mr-1"></i>
                                                                                Confirmar
                                                                            </button>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            @foreach ($notasDelDia as $key_2 => $nota)
                                                @php
                                                    // Mapeo de conceptos
                                                    $concepto_config = [
                                                        '1' => [
                                                            'color' => 'primary',
                                                            'icon' => 'book',
                                                            'title' => 'Conocimiento',
                                                        ],
                                                        '2' => [
                                                            'color' => 'success',
                                                            'icon' => 'lightbulb',
                                                            'title' => 'Aplicación',
                                                        ],
                                                        '3' => [
                                                            'color' => 'info',
                                                            'icon' => 'user-check',
                                                            'title' => 'Ética',
                                                        ],
                                                        '4' => [
                                                            'color' => 'warning',
                                                            'icon' => 'star',
                                                            'title' => 'Especial',
                                                        ],
                                                    ];
                                                    $config = $concepto_config[$nota['concepto_nota_id']] ?? [
                                                        'color' => 'secondary',
                                                        'icon' => 'file-alt',
                                                        'title' => 'General',
                                                    ];

                                                    // Determinar tipo de nota
                                                    $esNumero = is_numeric($nota['nota']);
                                                    $tipoClase = $esNumero ? 'nota-numerica' : 'nota-texto';

                                                    // Procesar texto con fechas
                                                    $texto = $nota['nota'];
                                                    $textoProcesado = $texto;
                                                    $patron = '/\b(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\b/';

                                                    if (preg_match_all($patron, $texto, $coincidencias)) {
                                                        $fechas = $coincidencias[0];
                                                        foreach ($fechas as $fechaTexto) {
                                                            $fecha_formateada = getSmallDate($fechaTexto);
                                                            $textoProcesado = str_replace(
                                                                $fechaTexto,
                                                                $fecha_formateada,
                                                                $textoProcesado,
                                                            );
                                                        }
                                                    }

                                                    // Convertir URLs en enlaces clickeables y agregar saltos de línea
                                                    $textoMostrar = nl2br(e($textoProcesado));
                                                    $textoMostrar = preg_replace(
                                                        '/(https?:\/\/[^\s]+)/',
                                                        '<a href="$1" target="_blank" class="text-info">$1</a>',
                                                        $textoMostrar,
                                                    );
                                                @endphp

                                                <!-- Tarjeta individual para cada nota -->
                                                <div
                                                    class="col-md-{{ $key_2 == 0 ? 4 : 2 }} col-lg-{{ $key_2 == 0 ? 6 : 2 }} mb-3">
                                                    <div
                                                        class="card card-success card-outline h-100 border-0 shadow-sm hover-lift {{ $tipoClase }}">
                                                        <!-- Indicador visual del tipo de nota -->
                                                        <div
                                                            class="card-type-indicator card-outline bg-{{ $esNumero ? 'warning' : 'info' }} text-white py-1 text-center">

                                                        </div>

                                                        <div class="card-body p-3">
                                                            <!-- Cabecera de la nota -->
                                                            <div class="d-flex align-items-center mb-3">
                                                                {{--   <div class="mr-3">
                                                                    <div class="bg-{{ $config['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center"
                                                                        style="width: 45px; height: 45px;">
                                                                        <i class="fas fa-{{ $config['icon'] }}"></i>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="flex-grow-1">
                                                                    <h6
                                                                        class="font-weight-bold mb-0 text-{{ $config['color'] }}">
                                                                        {!! $nota['concepto_nota'] !!}
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        {{-- {{ $config['title'] }} --}}
                                                                    </small>
                                                                </div>
                                                                @if ($esNumero)
                                                                    {{--  <div class="ml-2">
                                                                        <span
                                                                            class="badge badge-{{ $config['color'] }} badge-pill px-3 py-2 font-weight-bold"
                                                                            style="font-size: 1.1rem;">
                                                                            {{ $nota['nota'] }}
                                                                        </span>
                                                                    </div> --}}
                                                                @endif
                                                            </div>

                                                            <!-- Contenido de la nota -->
                                                            <div class="mb-3">
                                                                <div class="nota-contenido"
                                                                    style="max-height: 150px; overflow-y: auto;">
                                                                    @if ($esNumero)
                                                                        <div class="text-center py-2">
                                                                            <div
                                                                                class="display-4 text-{{ $config['color'] }} font-weight-bold">
                                                                                {{ $nota['nota'] }}
                                                                            </div>
                                                                            <small class="text-muted">Calificación
                                                                                numérica</small>
                                                                        </div>
                                                                    @else
                                                                        <p class="mb-0 ">
                                                                            {!! $textoMostrar !!}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <!-- Footer con metadatos -->

                                                            <hr>
                                                            <div class="border-top pt-3">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <small class="text-muted d-block">
                                                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                                                            {{ $nota['origen_nota'] }}
                                                                        </small>
                                                                        <small class="text-muted">
                                                                            <i class="far fa-clock mr-1"></i>
                                                                            {{ getSmallDateWithHour($nota['created_at']) }}
                                                                        </small>
                                                                    </div>
                                                                    @if ($key_2 == 0)
                                                                        <div class="text-right">
                                                                            <small class="text-primary font-weight-bold">
                                                                                <i class="fas fa-user-graduate mr-1"></i>
                                                                                {{ $nota['docevname'] }}
                                                                            </small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                        {{--  <input type="hidden" disabled
                                                            class="chk_notas-{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }} "
                                                            value="{{ $nota['id'] }}">  --}}
                                                        <div class="custom-check position-absolute"
                                                            style="top:10px; right:10px;">

                                                             <input type="checkbox"
                                                                class="chk_notas-{{ $key_nota }}-{{ $data[0]['tbl_org_id'] }} d-none check-modern"
                                                                value="{{ $nota['id'] }}" id="chk_{{ $nota['id'] }}-{{ $data[0]['tbl_org_id'] }}"
                                                                disabled> 

                                                            <label for="chk_{{ $nota['id'] }}-{{ $data[0]['tbl_org_id'] }}"
                                                                class="check-ui mb-0"></label>

                                                        </div> 

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Línea separadora entre bloques (excepto el último) -->
                                        @if (!$loop->last)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="separador-bloque text-center my-4">
                                                        <div
                                                            class="d-inline-block px-4 py-2 bg-white shadow-sm rounded-pill">
                                                            <small class="text-muted">
                                                                <i class="fas fa-arrow-down text-primary mr-1"></i>
                                                                Siguiente bloque de notas - Exp:
                                                                {{ $data[0]['expediente'] }}
                                                                <i class="fas fa-arrow-down text-primary ml-1"></i>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Estado vacío con diseño atractivo -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-clipboard-list fa-4x text-muted mb-4" style="opacity: 0.3;"></i>
                                </div>
                                <h3 class="text-muted font-weight-light mb-3">No hay notas registradas</h3>
                                <p class="text-muted mb-4">No se encontraron notas para mostrar en este momento.</p>
                                <div class="dotted-line mx-auto mb-4"
                                    style="width: 100px; border-top: 2px dashed #dee2e6;"></div>
                                <p class="text-muted small">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Las notas aparecerán aquí una vez que sean registradas en el sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Panel de confirmación de eliminación -->
        @foreach ($notas as $key => $data)
            @if (currentUser()->hasRole('amatai') || currentUser()->can('eliminar_notas'))
                <div id="confirmationPanel-{{ $key }}" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header border-0 bg-danger text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Confirmar eliminación
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="text-center mb-4">
                                    <div class="warning-icon mb-3">
                                        <i class="fas fa-trash-alt fa-3x text-danger"></i>
                                    </div>
                                    <h5 class="font-weight-bold mb-2">¿Estás seguro de eliminar estas notas?</h5>
                                    <p class="text-muted">Esta acción no se puede deshacer. Se eliminarán todas las
                                        notas
                                        del expediente {{ $data[0]['expediente'] }}.</p>
                                </div>
                                <div class="alert alert-warning">
                                    <div class="d-flex">
                                        <i class="fas fa-exclamation-circle mt-1 mr-3"></i>
                                        <div>
                                            <small class="font-weight-bold d-block">Advertencia:</small>
                                            <small>Los datos eliminados no podrán recuperarse.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </button>
                                <button id="btn_delete_notas_ver-{{ $key }}"
                                    class="btn btn-danger btn_delete_notas_ver" data-id="{{ $key }}">
                                    <i class="fas fa-check mr-1"></i> Sí, eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Panel de promedios -->
        @if (currentUser()->hasRole('amatai') and $contador_e > 0 and Request::has('segid') and Request::get('segid') != '')
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card shadow-lg border-0">
                        <div class="card-header border-0 text-white"
                            style="background: linear-gradient(135deg, #222d32 0%, #f39c12 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">
                                    <i class="fas fa-chart-pie mr-2"></i>
                                    Dashboard de Promedios
                                </h4>
                                <span class="badge badge-light badge-pill px-3 py-2">
                                    {{ $contador_c + $contador_a + $contador_e }} notas evaluadas
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-4">
                                    <div class="stats-card card border-0 h-100 shadow-sm">
                                        <div class="card-body text-center p-4">
                                            <div class="icon-wrapper mb-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-brain text-white"></i>
                                                </div>
                                            </div>
                                            <h3 class="font-weight-bold text-primary mb-2">
                                                {{ $contador_c > 0 ? number_format($promedio_c / $contador_c, 1, '.', ' ') : '0.0' }}
                                            </h3>
                                            <h6 class="text-uppercase text-muted mb-1">Conocimiento</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                {{ $contador_c }} evaluaciones
                                            </p>
                                            <div class="progress mt-3" style="height: 6px;">
                                                <div class="progress-bar bg-primary"
                                                    style="width: {{ ($promedio_c / $contador_c / 10) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-4">
                                    <div class="stats-card card border-0 h-100 shadow-sm">
                                        <div class="card-body text-center p-4">
                                            <div class="icon-wrapper mb-3">
                                                <div class="icon-circle bg-success">
                                                    <i class="fas fa-lightbulb text-white"></i>
                                                </div>
                                            </div>
                                            <h3 class="font-weight-bold text-success mb-2">
                                                {{ $contador_a > 0 ? number_format($promedio_a / $contador_a, 1, '.', ' ') : '0.0' }}
                                            </h3>
                                            <h6 class="text-uppercase text-muted mb-1">Aplicación</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                {{ $contador_a }} evaluaciones
                                            </p>
                                            <div class="progress mt-3" style="height: 6px;">
                                                <div class="progress-bar bg-success"
                                                    style="width: {{ ($promedio_a / $contador_a / 10) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-4">
                                    <div class="stats-card card border-0 h-100 shadow-sm">
                                        <div class="card-body text-center p-4">
                                            <div class="icon-wrapper mb-3">
                                                <div class="icon-circle bg-info">
                                                    <i class="fas fa-hands-helping text-white"></i>
                                                </div>
                                            </div>
                                            <h3 class="font-weight-bold text-info mb-2">
                                                {{ $contador_e > 0 ? number_format($promedio_e / $contador_e, 1, '.', ' ') : '0.0' }}
                                            </h3>
                                            <h6 class="text-uppercase text-muted mb-1">Ética</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-hashtag mr-1"></i>
                                                {{ $contador_e }} evaluaciones
                                            </p>
                                            <div class="progress mt-3" style="height: 6px;">
                                                <div class="progress-bar bg-info"
                                                    style="width: {{ ($promedio_e / $contador_e / 10) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-4">
                                    <div class="stats-card card border-0 h-100 shadow-sm">
                                        <div class="card-body text-center p-4">
                                            <div class="icon-wrapper mb-3">
                                                <div class="icon-circle bg-warning">
                                                    <i class="fas fa-star text-white"></i>
                                                </div>
                                            </div>
                                            <h3 class="font-weight-bold text-warning mb-2">
                                                {{ $contador_c > 0 && $contador_a > 0 && $contador_e > 0 ? number_format(($promedio_c / $contador_c + $promedio_a / $contador_a + $promedio_e / $contador_e) / 3, 1, '.', ' ') : '0.0' }}
                                            </h3>
                                            <h6 class="text-uppercase text-muted mb-1">Promedio General</h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-calculator mr-1"></i>
                                                Media ponderada
                                            </p>
                                            <div class="progress mt-3" style="height: 6px;">
                                                <div class="progress-bar bg-warning"
                                                    style="width: {{ (($promedio_c / $contador_c + $promedio_a / $contador_a + $promedio_e / $contador_e) / 30) * 100 }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Estilos personalizados -->


    <!-- Scripts -->



    <!-- Agregar estos estilos adicionales si no usas Font Awesome -->



    @include('myforms.notas_ver.modal_detalles')
@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script type="module" src={{ asset('js/admin_notas_ver.js?v=' . config('app_config.asset_version')) }}></script>
    <script>
        $(document).ready(function() {
            // Animación de acordeón

        });
    </script>
@endpush
