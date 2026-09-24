<!-- FECHA INICIAL -->
<div class="col-md-5">
    <div class="form-group">
        <label for="inicio" class="iuris-form-label">
            Fecha inicial
        </label>

        <div class="iuris-input-icon">
            <i class="far fa-calendar-alt"></i>

            <input value="" type="date" name="start" id="inicio" class="form-control">
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

            <input value="" type="date" name="end" id="fin" class="form-control">
        </div>
    </div>
</div>

<!-- BOTÓN -->
<div class="col-md-2 d-flex align-items-end">
    <div class="form-group w-100">
        <button id="btn_consultar_asis" type="button" class="btn btn-iuris-primary btn-block">
            <i class="fas fa-search mr-1"></i>
            Consultar
        </button>
    </div>
</div>




{{-- =====================================================
        TARJETAS DE RESUMEN
    ====================================================== --}}
<div class="iuris-attendance-cards">


    {{-- HORAS PROGRAMADAS --}}
    <div class="iuris-attendance-card">

        <div class="iuris-attendance-icon">
            <i class="fa fa-clock-o"></i>
        </div>

        <div class="iuris-attendance-data">

            <span>
                Horas programadas
            </span>

            <strong>
                8 h
            </strong>

            <small>
                Horas semanales
            </small>

        </div>

    </div>


    {{-- HORAS ASISTIDAS --}}
    <div class="iuris-attendance-card">

        <div class="iuris-attendance-icon">
            <i class="fa fa-check"></i>
        </div>

        <div class="iuris-attendance-data">

            <span>
                Horas asistidas
            </span>

            <strong>
                8 h
            </strong>

            <small>
                Asistencia registrada
            </small>

        </div>

    </div>


    {{-- HORAS PENDIENTES --}}
    <div class="iuris-attendance-card">

        <div class="iuris-attendance-icon">
            <i class="fa fa-hourglass-half"></i>
        </div>

        <div class="iuris-attendance-data">

            <span>
                Horas pendientes
            </span>

            <strong>
                0 min
            </strong>

            <small>
                Por registrar
            </small>

        </div>

    </div>


    {{-- NO ASISTENCIA --}}
    <div class="iuris-attendance-card">

        <div class="iuris-attendance-icon">
            <i class="fa fa-user-times"></i>
        </div>

        <div class="iuris-attendance-data">

            <span>
                No asistencia
            </span>

            <strong>
                0 h
            </strong>

            <small>
                Sin asistencia
            </small>

        </div>

    </div>


    {{-- POR REPONER --}}
    <div class="iuris-attendance-card iuris-attendance-card-warning">

        <div class="iuris-attendance-icon">
            <i class="fa fa-refresh"></i>
        </div>

        <div class="iuris-attendance-data">

            <span>
                Por reponer
            </span>

            <strong>
                0 h
            </strong>

            <small>
                Horas marcadas
            </small>

        </div>

    </div>

</div>


{{-- =====================================================
        HORARIO SEMANAL
    ====================================================== --}}
<div class="iuris-schedule-section">

    <div class="iuris-section-heading">

        <div>

            <span class="iuris-info-eyebrow">
                HORARIO ASIGNADO
            </span>

            <h4>
                Jornada semanal
            </h4>

        </div>

        <span class="iuris-schedule-total">
            <i class="fa fa-clock-o"></i>
            8 horas semanales
        </span>

    </div>


    @php
        $diasSemana = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
    @endphp

    <div class="iuris-schedule-grid">

        @foreach ($diasSemana as $dia)
            @php
               // $diaKey = \Illuminate\Support\Str::ascii(strtolower($dia));

                $turnos = $horarioDocente->get($dia, collect());

                //$turnos = $horarioDocente->get($dia, collect());

                $minutosDia = $turnos->sum(function ($turno) {
                    $inicio = \Carbon\Carbon::parse($turno->trnd_hora_inicio);
                    $fin = \Carbon\Carbon::parse($turno->trnd_hora_fin);

                    return $inicio->diffInMinutes($fin);
                });

                $horasDia = floor($minutosDia / 60);
                $minutosRestantes = $minutosDia % 60;
            @endphp

           
            <div class="iuris-day-card {{ $turnos->isEmpty() ? 'iuris-day-empty' : '' }}">

                {{-- CABECERA DEL DÍA --}}
                <div class="iuris-day-header">

                    <div class="iuris-day-icon">
                        <i class="fa fa-calendar-o"></i>
                    </div>

                    <div>
                        <strong>{{ $dia }}</strong>

                        @if ($turnos->isNotEmpty())
                            <span>
                                @if ($minutosRestantes > 0)
                                    {{ $horasDia > 0 ? $horasDia . ' h ' : '' }}{{ $minutosRestantes }} min
                                @else
                                    {{ $horasDia }} {{ $horasDia == 1 ? 'hora' : 'horas' }}
                                @endif
                            </span>
                        @else
                            <span>Sin horario</span>
                        @endif
                    </div>

                </div>


                {{-- TURNOS DEL DÍA --}}
                <div class="iuris-day-turnos">

                    @if ($turnos->isNotEmpty())
                        @foreach ($turnos as $turno)
                            <div class="iuris-time-block">

                                <div class="iuris-time">
                                    {{ \Carbon\Carbon::parse($turno->trnd_hora_inicio)->format('H:i') }}
                                </div>

                                <div class="iuris-time-line">
                                    <span></span>
                                </div>

                                <div class="iuris-time">
                                    {{ \Carbon\Carbon::parse($turno->trnd_hora_fin)->format('H:i') }}
                                </div>

                            </div>
                        @endforeach
                    @else
                        <div class="iuris-day-no-schedule">
                            <i class="fa fa-clock-o"></i>
                            <span>No hay horario asignado</span>
                        </div>
                    @endif

                </div>

            </div>
        @endforeach

    </div>

</div>
