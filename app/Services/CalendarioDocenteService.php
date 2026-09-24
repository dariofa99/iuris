<?php

namespace App\Services;

use App\Repositories\TurnosDocenteRepository;
use Carbon\Carbon;

class CalendarioDocenteService
{
    protected $turnosDocenteRepository;

    public function __construct(
        TurnosDocenteRepository $turnosDocenteRepository
    ) {
        $this->turnosDocenteRepository =
            $turnosDocenteRepository;
    }

    /**
     * Obtiene los turnos del periodo y los
     * convierte en eventos para FullCalendar.
     */
    public function obtenerEventos($periodo)
    {
        $turnos = $this->turnosDocenteRepository
            ->obtenerPorPeriodo($periodo->id);
        //return $turnos;
        return $this->generarEventos(
            $turnos,
            $periodo
        );
    }

    /**
     * Genera los eventos del calendario.
     */


    private function generarEventos(
        $turnos,
        $periodo
    ) {
        $eventos = [];


        /*
     * =====================================================
     * RANGO SOLICITADO POR FULLCALENDAR
     * =====================================================
     */

        $fechaInicio = Carbon::parse(
            request()->input('start')
        )->startOfDay();

        $fechaFin = Carbon::parse(
            request()->input('end')
        )->subDay()->endOfDay();


        /*
     * =====================================================
     * COLORES
     * =====================================================
     */

        $colores = [

            '#4A4A4A',
            '#6B6B6B',
            '#8C8C8C',
            '#A8A8A8',

            '#2C3E50',
            '#34495E',
            '#5D6D7E',
            '#7F8C8D',
            '#A9B7C6',

            '#4A6B5A',
            '#5F7D6B',
            '#7D9B8A',
            '#A8C4B4',

            '#6B4F3C',
            '#8B6F4F',
            '#A8896A',
            '#C4A88A',

            '#B8865A',
            '#C9A87C',
            '#D4B896',

            '#6B3A4A',
            '#8B4A5A',
            '#A86A7A',

            '#5B4A6B',
            '#7A6A8A',
            '#A89AB8',

            '#B87A5A',
            '#D49A7A',
            '#E0B89A',

            '#A8984A',
            '#C4B86A',
            '#D4CC8A',
        ];


        /*
     * =====================================================
     * COLOR POR DOCENTE
     * =====================================================
     */

        $coloresDocentes = [];

        foreach ($turnos as $turno) {

            $docente =
                $turno->trnd_docidnumber;

            if (!isset($coloresDocentes[$docente])) {

                $indice =
                    count($coloresDocentes)
                    % count($colores);

                $coloresDocentes[$docente] =
                    $colores[$indice];
            }
        }


        /*
     * =====================================================
     * DÍAS DE LA SEMANA
     * =====================================================
     */

        $diasSemana = [

            Carbon::MONDAY =>
            'Lunes',

            Carbon::TUESDAY =>
            'Martes',

            Carbon::WEDNESDAY =>
            'Miercoles',

            Carbon::THURSDAY =>
            'Jueves',

            Carbon::FRIDAY =>
            'Viernes',
        ];


        /*
     * =====================================================
     * 1. TURNOS ORIGINALES
     * =====================================================
     */

        for (
            $fecha = $fechaInicio->copy();
            $fecha->lte($fechaFin);
            $fecha->addDay()
        ) {

            /*
         * No fines de semana.
         */

            if ($fecha->isWeekend()) {
                continue;
            }


            /*
         * Día de la semana.
         */

            $numeroDia =
                $fecha->dayOfWeek;


            if (!isset($diasSemana[$numeroDia])) {
                continue;
            }


            $nombreDia =
                $diasSemana[$numeroDia];


            /*
         * =================================================
         * TURNOS DE ESTE DÍA
         * =================================================
         */

            $turnosDia = $turnos->filter(
                function ($turno) use ($nombreDia) {

                    return trim(
                        $turno->trnd_dia
                    ) === $nombreDia;
                }
            );


            /*
         * =================================================
         * GENERAR CADA TURNO
         * =================================================
         */

            foreach ($turnosDia as $turno) {

                /*
             * =============================================
             * DOCENTE
             * =============================================
             */

                $docente =
                    $turno->trnd_docidnumber;


                $nombre = trim(
                    $turno->name
                        . ' '
                        . $turno->lastname
                );


                $imagen =
                    url(
                        '/thumbnails/'
                            . $turno->image
                    );


                $color =
                    $coloresDocentes[$docente];


                /*
             * =============================================
             * FECHA ACTUAL
             * =============================================
             */

                $fechaActual =
                    $fecha->format('Y-m-d');


                /*
             * =============================================
             * ASISTENCIA DE ESTA FECHA
             * =============================================
             */

                $asistencia = null;


                if (
                    isset($turno->asistencias)
                    && $turno->asistencias
                ) {

                    $registrosFecha =
                        $turno->asistencias
                        ->get($fechaActual);


                    if ($registrosFecha) {

                        $asistencia =
                            $registrosFecha->first();
                    }
                }


                /*
             * =============================================
             * HORARIO ORIGINAL
             * =============================================
             */

                $inicio =
                    $fechaActual
                    . ' '
                    . $turno->trnd_hora_inicio;


                $fin =
                    $fechaActual
                    . ' '
                    . $turno->trnd_hora_fin;


                /*
             * =============================================
             * EVENTO DEL TURNO
             * =============================================
             */

                $evento = [

                    'id' =>

                    $turno->id,

                    'title' =>
                    $nombre,

                    'start' =>
                    $inicio,

                    'end' =>
                    $fin,

                    'fecha_turno' =>
                    $fechaActual,


                    /*
                 * COLOR
                 */

                    'backgroundColor' =>
                    $color,

                    'borderColor' =>
                    $color,

                    'textColor' =>
                    '#ffffff',


                    /*
                 * =========================================
                 * DOCENTE
                 * =========================================
                 */

                    'docidnumber' =>
                    $docente,

                    'nombre' =>
                    $nombre,

                    'dia' =>
                    $turno->trnd_dia,

                    'hora_inicio' =>
                    $turno->trnd_hora_inicio,

                    'hora_fin' =>
                    $turno->trnd_hora_fin,

                    'image' =>
                    $imagen,


                    /*
                 * =========================================
                 * ASISTENCIA
                 * =========================================
                 */

                    'asistencia_id' =>
                    $asistencia->id
                        ?? null,

                    'tipo_asis' =>
                    $asistencia->tipo_asis
                        ?? null,

                    'categoria' =>
                    $asistencia->categoria
                        ?? null,

                    'descripcion' =>
                    $asistencia->descripcion
                        ?? null,

                    'hora_inicio_asis' =>
                    $asistencia && $asistencia->hora_inicio_asis != null
                        ? Carbon::parse($asistencia->hora_inicio_asis)->format('H:i:s')
                        : null,

                    'hora_fin_asis' =>
                    $asistencia && $asistencia->hora_fin_asis != null
                        ? Carbon::parse($asistencia->hora_fin_asis)->format('H:i:s')
                        : null,

                    'tipo_asis_nombre' =>
                    $asistencia->tipo_asis_nombre
                        ?? 'Sin registrar aún',

                    'tipo_asis_color' =>
                    $asistencia->tipo_asis_color
                        ?? '#057f9a',


                    /*
                 * =========================================
                 * REPOSICIÓN
                 * =========================================
                 */

                    'es_reposicion' =>
                    false,

                    'reposicion_id' =>
                    $asistencia->id
                        ?? null,

                    'descripcion_reposicion' =>
                    null,

                    'hora_inicio_reposicion' =>
                    $asistencia && $asistencia->hora_inicio_asis != null
                        ? Carbon::parse($asistencia->hora_inicio_asis)->format('H:i:s')
                        : null,

                    'hora_fin_reposicion' =>
                    null,


                    /*
                 * =========================================
                 * REFERENCIA AL TURNO
                 * =========================================
                 */

                    'turno_docente_id' =>
                    $turno->id
                ];

                $evento['has_reposicion'] = false;
                if($turno->reposiciones && $turno->reposiciones->count() > 0){
                    $reposicion = $turno->reposiciones->first();
                    $evento['has_reposicion'] = true;

                    $evento['reposicion_id'] = $reposicion->id;

                    $evento['fecha_reposicion'] = Carbon::parse($reposicion->hora_inicio_asis)->format('Y-m-d')
                        ?? null;

                    $evento['descripcion_reposicion'] = $reposicion->descripcion_reposicion
                        ?? null;

                    $evento['hora_inicio_reposicion'] =
                    $reposicion->hora_inicio_asis && $reposicion->hora_inicio_asis != null
                        ? Carbon::parse($reposicion->hora_inicio_asis)->format('H:i:s')
                        : null;

                    $evento['hora_fin_reposicion'] =
                    $reposicion->hora_fin_asis && $reposicion->hora_fin_asis != null
                        ? Carbon::parse($reposicion->hora_fin_asis)->format('H:i:s')
                        : null;
                }

                $eventos[] = $evento;
            }
        }


        /*
     * =====================================================
     * 2. REPOSICIONES
     * =====================================================
     *
     * Una reposición = un evento.
     *
     * Pero SOLO si la fecha de la reposición está
     * dentro del rango solicitado por FullCalendar.
     */

        foreach ($turnos as $turno) {

            /*
         * =============================================
         * DOCENTE
         * =============================================
         */

            $docente =
                $turno->trnd_docidnumber;


            $nombre = trim(
                $turno->name
                    . ' '
                    . $turno->lastname
            );


            $imagen =
                url(
                    '/thumbnails/'
                        . $turno->image
                );


            $color =
                $coloresDocentes[$docente];


            /*
         * =============================================
         * REPOSICIONES
         * =============================================
         */

            if (
                !isset($turno->reposiciones)
                || !$turno->reposiciones
            ) {
                continue;
            }


            foreach (
                $turno->reposiciones
                as $reposicion
            ) {

                /*
             * =========================================
             * FECHA REAL DE LA REPOSICIÓN
             * =========================================
             */

                $inicioReposicion =
                    Carbon::parse(
                        $reposicion->hora_inicio_asis
                    );


                $finReposicion =
                    Carbon::parse(
                        $reposicion->hora_fin_asis
                    );


                /*
             * =========================================
             * VERIFICAR RANGO
             * =========================================
             *
             * Esto evita enviar reposiciones que están
             * fuera del rango que pidió FullCalendar.
             */

                if (
                    $inicioReposicion->lt($fechaInicio)
                    ||
                    $inicioReposicion->gt($fechaFin)
                ) {
                    continue;
                }


                /*
             * =========================================
             * ID
             * =========================================
             */

                $idEvento =
                    
                     $turno->id
                   ;


                /*
             * =========================================
             * EVENTO
             * =========================================
             */

                $eventos[] = [

                    'id' =>
                    $idEvento,

                    'title' =>
                    $nombre,

                    'start' =>
                    $inicioReposicion
                        ->format('Y-m-d H:i:s'),

                    'end' =>
                    $finReposicion
                        ->format('Y-m-d H:i:s'),

                    'fecha_turno' =>
                    $inicioReposicion
                        ->format('Y-m-d'),


                    /*
                 * COLOR
                 */

                    'backgroundColor' =>
                    $color,

                    'borderColor' =>
                    $color,

                    'textColor' =>
                    '#ffffff',


                    /*
                 * =========================================
                 * DOCENTE
                 * =========================================
                 */

                    'docidnumber' =>
                    $docente,

                    'nombre' =>
                    $nombre,

                    'dia' =>
                    $turno->trnd_dia,

                    'hora_inicio' =>
                    $turno->trnd_hora_inicio,

                    'hora_fin' =>
                    $turno->trnd_hora_fin,

                    'image' =>
                    $imagen,


                    /*
                 * =========================================
                 * REPOSICIÓN
                 * =========================================
                 */

                    'es_reposicion' =>
                    true,

                    'reposicion_id' =>
                    $reposicion->id,

                    'descripcion_reposicion' =>
                    $reposicion
                        ->descripcion_reposicion
                        ?? null,

                    'hora_inicio_reposicion' =>
                    $reposicion->hora_inicio_asis && $reposicion->hora_inicio_asis != null
                        ? Carbon::parse($reposicion->hora_inicio_asis)->format('H:i:s')
                        : null,

                    'hora_fin_reposicion' =>
                    $reposicion->hora_fin_asis && $reposicion->hora_fin_asis != null
                        ? Carbon::parse($reposicion->hora_fin_asis)->format('H:i:s')
                        : null,

                    /*
                 * =========================================
                 * TURNO ORIGINAL
                 * =========================================
                 */

                    'turno_docente_id' =>
                    $turno->id,


                    /*
                 * =========================================
                 * ASISTENCIA
                 * =========================================
                 */

                    'asistencia_id' =>
                    $reposicion->id,

                    'tipo_asis' =>
                    $reposicion->tipo_asis,

                    'categoria' =>
                    $reposicion->categoria,

                    'descripcion' =>
                    $reposicion->descripcion,

                    'hora_inicio_asis' =>
                    $reposicion->hora_inicio_asis && $reposicion->hora_inicio_asis != null
                        ? Carbon::parse($reposicion->hora_inicio_asis)->format('H:i:s')
                        : null,

                    'hora_fin_asis' =>
                    $reposicion->hora_fin_asis && $reposicion->hora_fin_asis != null
                        ? Carbon::parse($reposicion->hora_fin_asis)->format('H:i:s')
                        : null,

                    'tipo_asis_nombre' =>
                    $reposicion->tipo_asis_nombre
                        ?? 'Pendiente por asistir',

                    'tipo_asis_color' =>
                    $reposicion->tipo_asis_color
                        ?? '#057f9a'
                ];
            }
        }


        /*
     * =====================================================
     * RETORNAR
     * =====================================================
     */

        return $eventos;
    }
}
