@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@section('titulo_area')
    Agenda
@endsection
@section('area_buttons')

@endsection

@section('area_forms')

    <div class="row">
        <div class="col-md-12">

            <div class="iuris-horario-filters">

                <!-- Tipo de horario -->
                <div class="iuris-horario-filter">

                    <div class="iuris-horario-filter-icon">
                        <i class="far fa-calendar-alt"></i>
                    </div>

                    <div class="iuris-horario-filter-content">
                        <label for="horariourl">
                            Tipo de horario
                        </label>

                        <select class="form-control" id="horariourl">
                            <option value="estudiantes" @if ($tipo == 'estudiantes') selected @endif>
                                Horario estudiantes
                            </option>

                            <option value="docentes" @if ($tipo == 'docentes') selected @endif>
                                Horario docentes
                            </option>
                        </select>
                    </div>

                </div>


                <!-- Docente -->
                <div class="iuris-horario-filter iuris-docente-filter">

                    <div class="iuris-horario-filter-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>

                    <div class="iuris-horario-filter-content">
                        <label for="docente_id">
                            Docente
                        </label>

                        <select class="form-control" name="docente_id" id="docente_id">
                            <option value="">
                                Seleccione un docente
                            </option>

                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->idnumber }}" @if ($docente->idnumber == request()->get('docente_id')) selected @endif>
                                    {{ $docente->full_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body no-padding" style="margin: 5px 5px 5px 5px;">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div id="calendaridlist"></div>

    <input type="hidden" id="idestlistcal" value="">




    @include('myforms.turnos.frm_modal_registrar_turno_docente')

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
    {!! Html::script('plugins/fullcalendar/fullcalendar.min.js') !!}
    {!! Html::script('plugins/fullcalendar/dist/locale/es.js') !!}
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script type="module" src={{ asset('js/admin_horarios.js?v=' . config('app_config.asset_version')) }}></script>
    <script type="module" src={{ asset('js/admin_calendar_asisten_docente.js?v=' . config('app_config.asset_version')) }}>
    </script>

    <!-- Page specific script -->

    <style type="text/css">
        /*#btn_modal_req{visibility: hidden !important;}*/
    </style>
@endpush
