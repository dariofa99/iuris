<?php

namespace App\Services;

use App\Repositories\TurnosDocenteRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        // return $turnos;
        return $this->generarEventos(
            $turnos,
            $periodo
        );
    }

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

            $docente = $turno->trnd_docidnumber;

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

            if ($fecha->isWeekend()) {
                continue;
            }

            $numeroDia = $fecha->dayOfWeek;

            if (!isset($diasSemana[$numeroDia])) {
                continue;
            }

            $nombreDia = $diasSemana[$numeroDia];


            /*
         * =================================================
         * TURNOS DEL DÍA
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
         * GENERAR TURNOS
         * =================================================
         */

            foreach ($turnosDia as $turno) {

                $docente =
                    $turno->trnd_docidnumber;

                $nombre = trim(
                    $turno->name
                        . ' '
                        . $turno->lastname
                );

                $imagen = url(
                    '/thumbnails/'
                        . $turno->image
                );

                $color =
                    $coloresDocentes[$docente];


                /*
             * =============================================
             * FECHA DEL TURNO
             * =============================================
             */

                $fechaActual =
                    $fecha->format('Y-m-d');


                /*
             * =============================================
             * ASISTENCIA
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
             * HORARIO
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
             * EVENTO
             * ============================================= */
   
  
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
                 * DOCENTE
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
                 * ASISTENCIA
                 */

                    'asistencia_id' =>
                    $asistencia != null ? $asistencia->id : null,

                    'tipo_asis' =>
                    $asistencia != null ? $asistencia->tipo_asis : null,

                    'categoria' =>
                    $asistencia != null ? $asistencia->categoria : null,

                    'descripcion' =>
                    $asistencia != null ? $asistencia->descripcion : null,

                    'hora_inicio_asis' =>
                    $asistencia != null
                        && $asistencia->hora_inicio_asis
                        ? Carbon::parse(
                            $asistencia->hora_inicio_asis
                        )->format('H:i:s')
                        : null,

                    'hora_fin_asis' =>
                    $asistencia != null
                        && $asistencia->hora_fin_asis
                        ? Carbon::parse(
                            $asistencia->hora_fin_asis
                        )->format('H:i:s')
                        : null,

                    'tipo_asis_nombre' =>
                    $asistencia != null ? $asistencia->tipo_asis_nombre : "Pendiente por registrar",

                    'tipo_asis_color' =>
                    $asistencia != null ? $asistencia->tipo_asis_color : "#057f9a",


                    /*
                 * REPOSICIÓN
                 */

                    'es_reposicion' =>
                    false,

                    'has_reposicion' =>
                    false,

                    'reposicion_id' =>
                    null,

                    'fecha_reposicion' =>
                    null,

                    'descripcion_reposicion' =>
                    null,

                    'hora_inicio_reposicion' =>
                    null,

                    'hora_fin_reposicion' =>
                    null,


                    /*
                 * TURNO
                 */

                    'turno_docente_id' =>
                    $turno->id
                ];


                /*
             * =================================================
             * REVISAR REPOSICIÓN
             * =================================================
             */

                if (
                    $asistencia
                    && $asistencia->reposiciones
                    && $asistencia->reposiciones->isNotEmpty()
                ) {
                  
                    $reposicion =
                        $asistencia->reposiciones->first();

                    Log::info(
                        "REPOSICIÓN: " . json_encode($reposicion)
                    );
                    $evento['has_reposicion'] =
                        true;

                    $evento['reposicion_id'] =
                        $reposicion->id;


                    $evento['fecha_reposicion'] =
                        $reposicion->inicio
                        ? Carbon::parse(
                            $reposicion->inicio
                        )->format('Y-m-d')
                        : null;


                    $evento['descripcion_reposicion'] =
                        $reposicion->descripcion
                        ?? null;


                    $evento['hora_inicio_reposicion'] =
                        $reposicion->inicio
                        ? Carbon::parse(
                            $reposicion->inicio
                        )->format('H:i:s')
                        : null;


                    $evento['hora_fin_reposicion'] =
                        $reposicion->fin
                        ? Carbon::parse(
                            $reposicion->fin
                        )->format('H:i:s')
                        : null;
                }


                $eventos[] = $evento;

            }
        }


        /*
     * =====================================================
     * 2. EVENTOS DE REPOSICIONES
     * =====================================================
     */

        foreach ($turnos as $turno) {

            $docente =
                $turno->trnd_docidnumber;

            $nombre = trim(
                $turno->name
                    . ' '
                    . $turno->lastname
            );

            $imagen = url(
                '/thumbnails/'
                    . $turno->image
            );

            $color =
                $coloresDocentes[$docente];


            if (
                !isset($turno->asistencias)
                || !$turno->asistencias
            ) {
                continue;
            }


            /*
         * =============================================
         * DÍAS
         * =============================================
         */

            foreach (
                $turno->asistencias
                as $asistenciasDia
            ) {

                /*
             * =============================================
             * ASISTENCIAS
             * =============================================
             */

                foreach (
                    $asistenciasDia
                    as $asistencia
                ) {

                    /*
                 * =========================================
                 * REPOSICIONES DE LA ASISTENCIA
                 * =========================================
                 */

                    if (
                        !$asistencia->reposiciones
                        || $asistencia->reposiciones->isEmpty()
                    ) {
                        continue;
                    }


                    /*
                 * =========================================
                 * CADA REPOSICIÓN
                 * =========================================
                 */

                    foreach (
                        $asistencia->reposiciones
                        as $reposicion
                    ) {

                        if (
                            !$reposicion->inicio
                        ) {
                            continue;
                        }


                        /*
                     * =====================================
                     * INICIO
                     * =====================================
                     */

                        $inicioReposicion =
                            Carbon::parse(
                                $reposicion->inicio
                            );


                        /*
                     * =====================================
                     * FIN
                     * =====================================
                     */

                        $finReposicion =
                            $reposicion->fin
                            ? Carbon::parse(
                                $reposicion->fin
                            )
                            : $inicioReposicion
                            ->copy()
                            ->addHour();


                        /*
                     * =====================================
                     * RANGO FULLCALENDAR
                     * =====================================
                     */

                        if (
                            $inicioReposicion->lt(
                                $fechaInicio
                            )
                            ||
                            $inicioReposicion->gt(
                                $fechaFin
                            )
                        ) {
                            continue;
                        }


                        /*
                     * =====================================
                     * ID ÚNICO
                     * =====================================
                     */

                        $idEvento =
                            $turno->id;


                        /*
                     * =====================================
                     * EVENTO DE REPOSICIÓN
                     * =====================================
                     */
                  
                        Log::info(
                            $reposicion
                        );
                        $eventos[] = [

                            

                            'id' =>
                            $idEvento,

                            'title' =>
                            $nombre,

                            'start' =>
                            $inicioReposicion
                                ->format(
                                    'Y-m-d H:i:s'
                                ),

                            'end' =>
                            $finReposicion
                                ->format(
                                    'Y-m-d H:i:s'
                                ),

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
                         * DOCENTE
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
                         * REPOSICIÓN
                         */

                            'es_reposicion' =>
                            true,

                            'has_reposicion' =>
                            false,

                            'reposicion_id' =>
                            $reposicion->id,

                            'descripcion_reposicion' =>
                            $reposicion->descripcion
                                ?? null,

                            'hora_inicio_reposicion' =>
                            $reposicion->inicio
                                ? Carbon::parse(
                                    $reposicion->inicio
                                )->format('H:i:s')
                                : null,

                            'hora_fin_reposicion' =>
                            $reposicion->fin
                                ? Carbon::parse(
                                    $reposicion->fin
                                )->format('H:i:s')
                                : null,


                            /*
                         * ASISTENCIA DE REPOSICIÓN
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
                            $reposicion->inicio
                                ? Carbon::parse(
                                    $reposicion->inicio
                                )->format('H:i:s')
                                : null,

                            'hora_fin_asis' =>
                            $reposicion->fin
                                ? Carbon::parse(
                                    $reposicion->fin
                                )->format('H:i:s')
                                : null,

                            'tipo_asis_nombre' =>
                            $reposicion->tipoAsistencia->ref_nombre
                                ?? 'Pendiente por asistir',

                            'tipo_asis_color' =>
                            $reposicion->tipoAsistencia->color
                                ?? '#9e00fa',


                            /*
                         * ASISTENCIA ORIGINAL
                         */

                            'asistencia_origen_id' =>
                            $asistencia->id,


                            /*
                         * TURNO ORIGINAL
                         */

                            'turno_docente_id' =>
                            $turno->id
                        ];
                    }
                }
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
