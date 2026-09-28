@extends('layouts.dashboard')

@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush
@section('titulo_general')
    Turnos
@endsection

@section('titulo_area')

@endsection

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('area_forms')

    @include('msg.success')
    <div class="iuris-turnos-page">
        <header class="iuris-turnos-page-header">
            <div class="iuris-turnos-page-icon">
                <i class="far fa-calendar-alt" aria-hidden="true"></i>
            </div>
            <div>
                <span class="iuris-turnos-eyebrow">GESTIÓN ACADÉMICA</span>
                <h2>Horarios docentes</h2>
                <p>Administra la asignación semanal y consulta el reporte de asistencia.</p>
            </div>
        </header>

        <div class="card card-tabs iuris-turnos-panel">
            <div class="card-header iuris-turnos-tabs-header">
                <ul class="nav nav-tabs iuris-turnos-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active urlactive" id="horario-asistencia-tab" href="#horario-asistencia"
                            data-toggle="tab" role="tab" aria-controls="horario-asig" aria-selected="true">
                            <i class="far fa-clock" aria-hidden="true"></i>
                            Registro de asistencia
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link urlactive" id="asistencia-tab" data-toggle="tab" href="#asistencia_tab"
                            role="tab" aria-controls="asistencia_tab" aria-selected="false">
                            <i class="fas fa-chart-bar" aria-hidden="true"></i>
                            Reporte asistencia
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link urlactive" id="horario-asig-tab" href="#horario-asig" data-toggle="tab"
                            role="tab" aria-controls="horario-asig" aria-selected="true">
                            <i class="far fa-clock" aria-hidden="true"></i>
                            Asignación horario
                        </a>
                    </li>

                </ul>
            </div><!-- /.card-header -->
            <div class="card-body iuris-turnos-panel-body">
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="horario-asistencia" role="tabpanel"
                        aria-labelledby="horario-asistencia-tab">
                        @include('myforms.turnos.calendario_asistencia_docentes')
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane " id="horario-asig" role="tabpanel" aria-labelledby="horario-asig-tab">

                        <div class="iuris-schedule-toolbar">
                            <div class="iuris-schedule-filter">
                                <div class="iuris-schedule-filter-icon">
                                    <i class="fas fa-user-tie" aria-hidden="true"></i>
                                </div>
                                <div class="iuris-schedule-filter-content">
                                    <label for="select_doc_horario">Seleccionar docente</label>
                                    <select class="form-control" id="select_doc_horario">
                                        <option value="0">Seleccione...</option>
                                        @foreach ($docentes as $docente)
                                            <option value="{{ $docente->idnumber }}">{{ $docente->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="iuris-selected-teacher" aria-live="polite">
                                <i class="fas fa-id-badge" aria-hidden="true"></i>
                                <span id="name_doc_horairo">Seleccione un docente</span>
                            </div>
                        </div>

                        <div class="row" id="content-docentetr" style="display: none">
                            <div class="col-md-12">
                                <section class="iuris-schedule-editor" aria-label="Editor de horario">
                                    <div class="iuris-schedule-editor-heading">
                                        <div>
                                            <span class="iuris-schedule-kicker">HORARIO SEMANAL</span>
                                            <h3>Turnos asignados</h3>
                                        </div>
                                        <span class="iuris-schedule-weekdays"><i class="far fa-calendar-check"
                                                aria-hidden="true"></i> Lunes a viernes</span>
                                    </div>

                                    <div class="box-body table-responsive no-padding iuris-schedule-table-wrap">
                                        <table class="normal-table table-list-est-tur table iuris-schedule-editor-table"
                                            id="table_turnos_docentes">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Hora inicio</th>
                                                    <th scope="col">Hora fin</th>
                                                    <th scope="col">Lun</th>
                                                    <th scope="col">Mar</th>
                                                    <th scope="col">Mié</th>
                                                    <th scope="col">Jue</th>
                                                    <th scope="col">Vie</th>
                                                    <th scope="col" aria-label="Acciones"><span
                                                            class="sr-only">Acciones</span></th>
                                                </tr>
                                            </thead>
                                            <tbody id="new_dia_docente"></tbody>
                                        </table>
                                    </div>

                                    <div class="iuris-schedule-actions">
                                        <div class="iuris-schedule-actions-primary">
                                            <button type="button" id="guardar_horario_doc" class="btn btn-iuris-primary">
                                                <i class="fas fa-save" aria-hidden="true"></i> Guardar cambios
                                            </button>
                                            <button type="button" id="horariomas" value="1"
                                                class="btn btn-iuris-add">
                                                <i class="fas fa-plus" aria-hidden="true"></i> Agregar turno
                                            </button>
                                        </div>
                                        <button type="button" id="inhabilitar_horario_doc"
                                            class="btn btn-iuris-disable">
                                            <i class="fas fa-ban" aria-hidden="true"></i> Inhabilitar horario
                                        </button>
                                    </div>
                                </section>
                            </div>
                        </div>


                    </div>
                    <!-- /.tab-pane -->

                    <!-- /.tab-pane -->
                    <div id="asistencia_tab" class="tab-pane fade" role="tabpanel" aria-labelledby="asistencia-tab">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="iuris-form-card">

                                    <!-- CABECERA -->
                                    <div class="iuris-form-header">
                                        <div class="d-flex align-items-center">
                                            <div class="iuris-summary-icon mr-3">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>

                                            <div>
                                                <div class="iuris-form-title">
                                                    Período de consulta
                                                </div>

                                                <small class="iuris-text-muted">
                                                    Seleccione el rango de fechas para consultar la información
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="#" method="get">

                                        <!-- CUERPO -->
                                        <div class="iuris-form-body">

                                            <div class="iuris-section-title">
                                                <i class="far fa-calendar-alt"></i>
                                                Rango de fechas
                                            </div>

                                            <div class="row">

                                                <!-- FECHA INICIAL -->
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inicio" class="iuris-form-label">
                                                            Fecha inicial
                                                        </label>

                                                        <div class="iuris-input-icon">
                                                            <i class="far fa-calendar-alt"></i>

                                                            <input value="{{ request()->get('start') }}" type="date"
                                                                name="start" id="inicio" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- FECHA FINAL -->
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="fin" class="iuris-form-label">
                                                            Fecha final
                                                        </label>

                                                        <div class="iuris-input-icon">
                                                            <i class="far fa-calendar-alt"></i>

                                                            <input value="{{ request()->get('end') }}" type="date"
                                                                name="end" id="fin" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- BOTÓN -->
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <div class="form-group w-100">
                                                        <button id="btn_consultar_asis" type="button"
                                                            class="btn btn-iuris-primary btn-block">
                                                            <i class="fas fa-search mr-1"></i>
                                                            Consultar
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <!-- FOOTER -->
                                        {{--      <div class="iuris-form-footer">
                                        <div class="iuris-help-text">
                                            <i class="fas fa-info-circle"></i>
                                            Seleccione las fechas que desea consultar.
                                        </div>
                                    </div> --}}

                                    </form>

                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-body table-responsive no-padding">
                                    <table id="tbl_repor_asis" class="table table-bordered table-striped dataTable"
                                        role="grid">

                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th>Horas semanales (rango)</th>
                                                <th>Horas asistidas</th>
                                                <th>Horas pendientes</th>
                                                <th>Horas no asistencia</th>
                                                <th>Horas marcadas por reponer</th>


                                            </tr>
                                        </thead>
                                        <tbody id="contenrepasistenciadoc">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>




                    <!-- /.tab-pane -->

                    <!-- /.tab-pane -->


                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
    </div>


@stop

@push('scripts')
    {!! Html::script('plugins/fullcalendar/fullcalendar.min.js') !!}
    {!! Html::script('plugins/fullcalendar/dist/locale/es.js') !!}
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script type="module" src={{ asset('js/admin_horarios.js?v=' . config('app_config.asset_version')) }}></script>
    <script type="module" src={{ asset('js/admin_calendar_asisten_docente.js?v=' . config('app_config.asset_version')) }}>
    </script>
    
    <script type="module" src={{ asset('js/admin_turnos.js?v=' . config('app_config.asset_version')) }}></script>
@endpush
