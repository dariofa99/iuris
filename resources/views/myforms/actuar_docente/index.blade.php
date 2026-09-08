@extends('layouts.dashboard')


@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush


@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection


@section('titulo_general')



@endsection


@section('titulo_area')



@endsection


@section('area_forms')

    @include('msg.alerts')


    <div class="container-fluid">


        {{-- ==========================================================
             CABECERA
        =========================================================== --}}

        <div class="iuris-form-card mb-4">

            <div class="iuris-form-header">

                <form class="w-100" id="form_actuar_docente" method="GET">

                    <div class="d-flex justify-content-between align-items-center iuris-periodo">



                        <div class="d-flex align-items-center">

                            {{-- Foto --}}

                            <div class="mr-3">

                                <div class="iuris-docente-photo">


                                    @if (!empty($docente->image) && file_exists(public_path('/thumbnails/' . $docente->image)))
                                        <img src="{{ asset('/thumbnails/' . $docente->image) }}" alt="">
                                    @else
                                        <i class="fas fa-user-tie"></i>
                                    @endif





                                </div>

                            </div>


                            {{-- Información --}}

                            <div>

                                <div class="iuris-form-title">

                                    Actuar del docente

                                </div>


                                <div class="mt-2">

                                    <select id="select_docidnumber" name="docidnumber"
                                        class="form-control iuris-docente-select">

                                        <option value="" disabled selected>
                                            Seleccione un docente
                                        </option>
                                        @foreach ($docentes as $docente)
                                            <option value="{{ $docente->idnumber }}"
                                                {{ request()->docidnumber == $docente->idnumber ? 'selected' : '' }}>
                                                {{ $docente->full_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>


                                <small class="iuris-text-muted">

                                    Documento:

                                    <strong class="iuris-text-muted">

                                        {{ $docente->idnumber ?? 'N/A' }}

                                    </strong>

                                </small>

                            </div>

                        </div>




                        <div class="">

                            <div class="iuris-label mb-2">
                                <i class="far fa-calendar-alt mr-1"></i>
                                Período de consulta
                            </div>

                            <div class="row">

                                {{-- Fecha --}}
                                <div class="col-md-4">
                                    <div class="form-group mb-0">

                                        <label class="iuris-period-label">
                                            Fecha
                                        </label>

                                        <div class="iuris-input-icon">
                                            <i class="far fa-calendar-alt"></i>

                                            <input type="date" name="fecha" class="form-control" value="{{ request()->fecha ?? Carbon\Carbon::now()->format('Y-m-d') }}"
                                                required>

                                        </div>

                                    </div>
                                </div>


                                {{-- Hora inicio --}}
                                <div class="col-md-4">
                                    <div class="form-group mb-0">

                                        <label class="iuris-period-label">
                                            Hora inicio
                                        </label>

                                        <div class="iuris-input-icon">
                                            <i class="far fa-clock"></i>

                                            <input type="time" name="hora_inicio" class="form-control" value="{{ $validate['hora_inicio'] ?? '08:00' }}"
                                                required>

                                        </div>

                                    </div>
                                </div>


                                {{-- Hora fin --}}
                                <div class="col-md-4">
                                    <div class="form-group mb-0">

                                        <label class="iuris-period-label">
                                            Hora fin
                                        </label>

                                        <div class="iuris-input-icon">
                                            <i class="far fa-clock"></i>

                                            <input type="time" name="hora_fin" class="form-control" value="{{ request()->hora_fin ?? '18:00' }}"
                                                required>

                                        </div>

                                    </div>
                                </div>

                            </div>


                            {{-- Botón consultar --}}
                            <div class="text-right mt-3">

                                <button type="submit" class="btn btn-iuris-primary">

                                    <i class="fas fa-search mr-1"></i>

                                    Consultar actividad

                                </button>

                            </div>

                        </div>





                    </div>

                </form>

            </div>

        </div>



        {{-- ==========================================================
             SIN RESULTADOS
        =========================================================== --}}

        @if ($resultado['expedientes']->isEmpty())


            <div class="iuris-form-card">

                <div class="iuris-form-body text-center" style="padding:50px 20px;">

                    <div class="iuris-header-icon mx-auto mb-3">

                        <i class="fas fa-folder-open"></i>

                    </div>


                    <h5 class="iuris-text-secondary font-weight-bold mb-2">

                        Sin actividad registrada

                    </h5>


                    <p class="iuris-text mb-0">

                        No se encontraron actividades del docente
                        durante el período seleccionado.

                    </p>

                </div>

            </div>
        @else
            {{-- ==========================================================
                 RESUMEN
            =========================================================== --}}

            <div class="row mb-4">


                {{-- ==================================================
                     EXPEDIENTES
                =================================================== --}}

                <div class="col-md-4">

                    <div class="iuris-form-card">

                        <div class="iuris-form-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <div class="label">

                                        Expedientes

                                    </div>


                                    <div class="iuris-summary-number">

                                        {{ $resultado['expedientes']->count() }}

                                    </div>

                                </div>


                                <div class="iuris-summary-icon">

                                    <i class="fas fa-folder"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     ACTIVIDADES
                =================================================== --}}

                <div class="col-md-4">

                    <div class="iuris-form-card">

                        <div class="iuris-form-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <div class="label">

                                        Actividades

                                    </div>


                                    <div class="iuris-summary-number success">

                                        {{ $resultado['expedientes']->sum(function ($item) {
                                            return count($item['actividades']);
                                        }) }}

                                    </div>

                                </div>


                                <div class="iuris-summary-icon success">

                                    <i class="fas fa-tasks"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                     DOCENTE
                =================================================== --}}

                {{--         <div class="col-md-4">

                    <div class="iuris-form-card">

                        <div class="iuris-form-body">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <div class="iuris-label">

                                        Docente

                                    </div>


                                    <div class="iuris-summary-number document">

                                        {{ $resultado['docente'] }}

                                    </div>

                                </div>


                                <div class="iuris-summary-icon">

                                    <i class="fas fa-user"></i>

                                </div>

                            </div>

                        </div>

                    </div>

                </div> --}}

            </div>



            {{-- ==========================================================
                 TÍTULO EXPEDIENTES
            =========================================================== --}}
            {{-- 
            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <div class="iuris-form-title">

                        Expedientes con actividad

                    </div>


                    <small class="iuris-text-muted">

                        Actividad registrada durante el período seleccionado

                    </small>

                </div>

            </div> --}}



            {{-- ==========================================================
                 EXPEDIENTES
            =========================================================== --}}

            @foreach ($resultado['expedientes'] as $index => $item)
                @php

                    $expediente = $item['expediente'];
                    $actividades = $item['actividades'];

                @endphp


                <div class="iuris-form-card mb-4">


                    {{-- ==================================================
                         CABECERA EXPEDIENTE
                    =================================================== --}}

                    <div class="form-card-header">

                        <div class="row align-items-center">


                            {{-- Expediente --}}

                            <div class="col-md-8">

                                <div class="d-flex align-items-center">

                                    <div class="iuris-expediente-icon">

                                        <i class="fas fa-folder"></i>

                                    </div>


                                    <div>

                                        <div class="iuris-label" style="color: #000000 !important;">

                                            Expediente

                                        </div>


                                        <h5 class="mb-0 mt-1">

                                            <a target="_blank" class="iuris-expediente-link"
                                                href="{{ url('expedientes/' . $expediente . '/edit') }}">

                                                {{ $expediente }}

                                            </a>

                                        </h5>

                                    </div>

                                </div>

                            </div>


                            {{-- Cantidad --}}

                            <div class="col-md-4 text-md-right mt-2 mt-md-0">

                                <span class="iuris-badge">

                                    <i class="fas fa-list mr-1"></i>

                                    {{ count($actividades) }}

                                    {{ count($actividades) == 1 ? 'actividad' : 'actividades' }}

                                </span>

                            </div>

                        </div>

                    </div>



                    {{-- ==================================================
                         ACTIVIDADES
                    =================================================== --}}

                    <div class="iuris-form-body p-0">


                        @if (empty($actividades))
                            <div class="text-center py-4 iuris-text-muted">

                                No se encontraron actividades.

                            </div>
                        @else
                            <div class="table-responsive">


                                <table class="table table-hover mb-0">


                                    <thead>

                                        <tr class="iuris-table-header">


                                            <th style="width:120px;">

                                                Hora

                                            </th>


                                            <th style="width:180px;">

                                                Actividad

                                            </th>


                                            <th>

                                                Detalle

                                            </th>


                                            <th style="width:100px;">

                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                        @foreach ($actividades as $actividad)
                                            @php

                                                $datos = $actividad['datos'];

                                            @endphp


                                            <tr>


                                                {{-- ==================================================
                                                     HORA
                                                =================================================== --}}

                                                <td>

                                                    <div class="iuris-activity-hour">

                                                        {{ $actividad['fecha']->format('H:i:s') }}

                                                    </div>


                                                    <small class="iuris-activity-date">

                                                        {{ $actividad['fecha']->format('d/m/Y') }}

                                                    </small>

                                                </td>



                                                {{-- ==================================================
                                                     TIPO
                                                =================================================== --}}

                                                <td>


                                                    @if ($actividad['tipo'] == 'actuacion')
                                                        <span class="iuris-activity-badge">

                                                            <i class="fas fa-file-alt mr-1"></i>

                                                            Actuación

                                                        </span>
                                                    @elseif($actividad['tipo'] == 'evaluacion')
                                                        <span class="iuris-activity-badge success">

                                                            <i class="fas fa-star mr-1"></i>

                                                            Evaluación

                                                        </span>
                                                    @elseif($actividad['tipo'] == 'asesoria')
                                                        <span class="iuris-activity-badge info">

                                                            <i class="fas fa-comments mr-1"></i>

                                                            Asesoría

                                                        </span>
                                                    @elseif($actividad['tipo'] == 'citacion')
                                                        <span class="iuris-activity-badge warning">

                                                            <i class="fas fa-calendar mr-1"></i>

                                                            Citación

                                                        </span>
                                                    @elseif($actividad['tipo'] == 'cambio_estado')
                                                        <span class="iuris-activity-badge state">

                                                            <i class="fas fa-exchange-alt mr-1"></i>

                                                            Estado

                                                        </span>
                                                    @else
                                                        <span class="iuris-activity-badge">

                                                            {{ ucfirst($actividad['tipo']) }}

                                                        </span>
                                                    @endif


                                                </td>



                                                {{-- ==================================================
                                                     DETALLE
                                                =================================================== --}}

                                                <td>


                                                    @if ($actividad['tipo'] == 'actuacion')
                                                        <div class="iuris-activity-title">

                                                            {{ $datos->actnombre }}

                                                        </div>


                                                        <small class="iuris-activity-description">

                                                            {{ Str::limit($datos->actdescrip, 150) }}

                                                        </small>
                                                    @elseif($actividad['tipo'] == 'evaluacion')
                                                        <div class="iuris-activity-title">

                                                            Evaluación del estudiante

                                                        </div>


                                                        <small class="iuris-activity-description">

                                                            Evaluación registrada por el docente

                                                        </small>
                                                    @elseif($actividad['tipo'] == 'asesoria')
                                                        <div class="iuris-activity-title">

                                                            Asesoría al estudiante

                                                        </div>


                                                        <small class="iuris-activity-description">

                                                            {{ Str::limit($datos->comentario ?? '', 150) }}

                                                        </small>
                                                    @elseif($actividad['tipo'] == 'citacion')
                                                        <div class="iuris-activity-title">

                                                            Citación al estudiante

                                                        </div>


                                                        <small class="iuris-activity-description">

                                                            {{ Str::limit($datos->motivo ?? '', 150) }}

                                                        </small>
                                                    @elseif($actividad['tipo'] == 'cambio_estado')
                                                        <div class="iuris-activity-title">

                                                            Cambio de estado

                                                        </div>


                                                        <small class="iuris-activity-description">

                                                            {{ Str::limit($datos->comentario ?? '', 150) }}

                                                        </small>
                                                    @endif


                                                </td>



                                                {{-- ==================================================
                                                     ACCIÓN
                                                =================================================== --}}

                                                <td class="text-right">


                                                    <button type="button" class="btn-iuris-info"
                                                        style="
                                                            width:32px;
                                                            height:32px;
                                                            padding:0;
                                                        "
                                                        data-toggle="modal"
                                                        data-target="#actividadModal{{ $index }}{{ $loop->index }}">

                                                        <i class="fas fa-eye"></i>

                                                    </button>


                                                </td>

                                            </tr>



                                            {{-- =================================================
                                                 MODAL DETALLE
                                            ================================================== --}}

                                            <div class="modal fade"
                                                id="actividadModal{{ $index }}{{ $loop->index }}" tabindex="-1"
                                                role="dialog">

                                                <div class="modal-dialog modal-lg" role="document">

                                                    <div class="modal-content iuris-modal-content">


                                                        {{-- ==================================================
                                                             HEADER MODAL
                                                        =================================================== --}}

                                                        <div class="modal-header iuris-modal-header">


                                                            <h5 class="modal-title">

                                                                <i class="fas fa-history mr-2"></i>

                                                                Detalle de actividad

                                                            </h5>


                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal">

                                                                <span>&times;</span>

                                                            </button>


                                                        </div>



                                                        {{-- ==================================================
                                                             BODY MODAL
                                                        =================================================== --}}

                                                        <div class="modal-body p-4">


                                                            <div class="row">


                                                                {{-- Expediente --}}

                                                                <div class="col-md-6">

                                                                    <div class="iuris-modal-label">

                                                                        Expediente

                                                                    </div>


                                                                    <div class="iuris-modal-value mb-3">

                                                                        {{ $expediente }}

                                                                    </div>

                                                                </div>


                                                                {{-- Fecha --}}

                                                                <div class="col-md-6">

                                                                    <div class="iuris-modal-label">

                                                                        Fecha

                                                                    </div>


                                                                    <div class="iuris-modal-value mb-3">

                                                                        {{ $actividad['fecha']->format('d/m/Y H:i:s') }}

                                                                    </div>

                                                                </div>


                                                            </div>


                                                            <hr class="iuris-divider">



                                                            {{-- ==================================================
                                                                 ACTUACIÓN
                                                            =================================================== --}}

                                                            @if ($actividad['tipo'] == 'actuacion')
                                                                <h6 class="iuris-activity-title">

                                                                    {{ $datos->actnombre }}

                                                                </h6>


                                                                <p class="iuris-text-secondary">

                                                                    {{ $datos->actdescrip }}

                                                                </p>


                                                                @if (!empty($datos->actdocenrecomendac) && $datos->actdocenrecomendac != '.')
                                                                    <div class="iuris-info-box">


                                                                        <strong class="iuris-info-box-title">

                                                                            Recomendación docente:

                                                                        </strong>


                                                                        <div class="iuris-info-box-text">

                                                                            {{ $datos->actdocenrecomendac }}

                                                                        </div>


                                                                    </div>
                                                                @endif



                                                                {{-- ==================================================
                                                                 EVALUACIÓN
                                                            =================================================== --}}
                                                            @elseif($actividad['tipo'] == 'evaluacion')
                                                                <div class="iuris-success-box">


                                                                    <i class="fas fa-star mr-2"></i>


                                                                    <strong>

                                                                        Evaluación realizada por el docente.

                                                                    </strong>


                                                                </div>



                                                                @if (is_array($datos))
                                                                    <div class="table-responsive mt-3">


                                                                        <div class="row py-2"
                                                                            style="border-bottom:1px solid #edf1f2;">

                                                                            <div class="col-md-6">

                                                                                <strong class="iuris-text-secondary">

                                                                                    Conocimiento

                                                                                </strong>

                                                                            </div>


                                                                            <div class="col-md-6">

                                                                                {{ $datos['conocimiento'] ?? '' }}

                                                                            </div>

                                                                        </div>


                                                                        <div class="row py-2"
                                                                            style="border-bottom:1px solid #edf1f2;">

                                                                            <div class="col-md-6">

                                                                                <strong class="iuris-text-secondary">

                                                                                    Aplicación

                                                                                </strong>

                                                                            </div>


                                                                            <div class="col-md-6">

                                                                                {{ $datos['aplicacion'] ?? '' }}

                                                                            </div>

                                                                        </div>


                                                                        <div class="row py-2"
                                                                            style="border-bottom:1px solid #edf1f2;">

                                                                            <div class="col-md-6">

                                                                                <strong class="iuris-text-secondary">

                                                                                    Ética

                                                                                </strong>

                                                                            </div>


                                                                            <div class="col-md-6">

                                                                                {{ $datos['etica'] ?? '' }}

                                                                            </div>

                                                                        </div>


                                                                        <div class="row py-2">

                                                                            <div class="col-md-6">

                                                                                <strong class="iuris-text-secondary">

                                                                                    Concepto

                                                                                </strong>

                                                                            </div>


                                                                            <div class="col-md-6">

                                                                                {{ $datos['concepto'] ?? '' }}

                                                                            </div>

                                                                        </div>


                                                                    </div>
                                                                @endif



                                                                {{-- ==================================================
                                                                 ASESORÍA
                                                            =================================================== --}}
                                                            @elseif($actividad['tipo'] == 'asesoria')
                                                                <strong class="iuris-text-primary">

                                                                    Comentario:

                                                                </strong>


                                                                <p class="mt-2 iuris-text-secondary">

                                                                    {{ $datos->comentario ?? '' }}

                                                                </p>



                                                                {{-- ==================================================
                                                                 CITACIÓN
                                                            =================================================== --}}
                                                            @elseif($actividad['tipo'] == 'citacion')
                                                                <strong class="iuris-text-primary">

                                                                    Motivo:

                                                                </strong>


                                                                <p class="mt-2 iuris-text-secondary">

                                                                    {{ $datos->motivo ?? '' }}

                                                                </p>



                                                                {{-- ==================================================
                                                                 CAMBIO ESTADO
                                                            =================================================== --}}
                                                            @elseif($actividad['tipo'] == 'cambio_estado')
                                                                <strong class="iuris-text-primary">

                                                                    Comentario:

                                                                </strong>


                                                                <p class="mt-2 iuris-text-secondary">

                                                                    {{ $datos->comentario ?? '' }}

                                                                </p>
                                                            @endif


                                                        </div>



                                                        {{-- ==================================================
                                                             FOOTER MODAL
                                                        =================================================== --}}

                                                        <div class="iuris-modal-footer">


                                                            <div class="iuris-required">

                                                                <i class="fas fa-info-circle"></i>

                                                                Información de la actividad

                                                            </div>


                                                            <button type="button" class="btn-iuris-primary"
                                                                data-dismiss="modal">

                                                                Cerrar

                                                            </button>


                                                        </div>


                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach


                                    </tbody>

                                </table>

                            </div>
                        @endif


                    </div>

                </div>
            @endforeach


        @endif


    </div>


@stop


@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#select_docidnumber').on('change', function() {
                //$('#form_actuar_docente').submit();
            });
        });
    </script>
@endpush
