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

        $fechaInicio = Carbon::parse(
            $periodo->prdfecha_inicio
        );

        $fechaFin = Carbon::parse(
            $periodo->prdfecha_fin
        );

        /*
         * Colores para identificar docentes.
         */
        $colores = [
            // Neutros y grises con matiz
            '#4A4A4A', // Gris oscuro
            '#6B6B6B', // Gris medio
            '#8C8C8C', // Gris claro
            '#A8A8A8', // Gris plata

            // Azules apagados
            '#2C3E50', // Azul pizarra oscuro
            '#34495E', // Azul grisáceo
            '#5D6D7E', // Azul acero
            '#7F8C8D', // Azul gris
            '#A9B7C6', // Azul muy claro

            // Verdes apagados / salvia
            '#4A6B5A', // Verde bosque
            '#5F7D6B', // Verde salvia
            '#7D9B8A', // Verde grisáceo
            '#A8C4B4', // Verde menta suave

            // Terrosos / marrones
            '#6B4F3C', // Marrón oscuro
            '#8B6F4F', // Marrón medio
            '#A8896A', // Marrón claro
            '#C4A88A', // Beige

            // Tostados y cálidos suaves
            '#B8865A', // Ocre apagado
            '#C9A87C', // Arena
            '#D4B896', // Crema

            // Rojos / burdeos apagados
            '#6B3A4A', // Burdeos oscuro
            '#8B4A5A', // Rojo apagado
            '#A86A7A', // Rosa terroso

            // Morados / lavanda apagada
            '#5B4A6B', // Morado grisáceo
            '#7A6A8A', // Lila apagado
            '#A89AB8', // Lavanda suave

            // Naranjas / melocotón apagado
            '#B87A5A', // Terracota
            '#D49A7A', // Melocotón suave
            '#E0B89A', // Durazno claro

            // Amarillos / mostaza apagada
            '#A8984A', // Mostaza
            '#C4B86A', // Amarillo apagado
            '#D4CC8A', // Amarillo muy suave
        ];

        /*
         * Asignar un color por docente.
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
         * Relación entre nombre del día
         * y número de día de Carbon.
         */
        $diasSemana = [
            Carbon::MONDAY => 'Lunes',
            Carbon::TUESDAY => 'Martes',
            Carbon::WEDNESDAY => 'Miercoles',
            Carbon::THURSDAY => 'Jueves',
            Carbon::FRIDAY => 'Viernes',
        ];

        /*
         * Recorremos todas las fechas
         * del periodo.
         */
        for (
            $fecha = $fechaInicio->copy();
            $fecha->lte($fechaFin);
            $fecha->addDay()
        ) {

            /*
             * No generar eventos para sábado
             * ni domingo.
             */
            if ($fecha->isWeekend()) {
                continue;
            }

            $numeroDia = $fecha->dayOfWeek;

            if (!isset($diasSemana[$numeroDia])) {
                continue;
            }

            $nombreDia =
                $diasSemana[$numeroDia];

            /*
             * Obtener los turnos correspondientes
             * a ese día.
             */
            $turnosDia = $turnos->filter(
                function ($turno) use ($nombreDia) {

                    return $turno->trnd_dia ===
                        $nombreDia;
                }
            );

            /*
             * Cada registro es un evento independiente.
             *
             * NO SE AGRUPA.
             */
            foreach ($turnosDia as $turno) {

                $fechaTexto =
                    $fecha->format('Y-m-d');

                $inicio =
                    $fechaTexto
                    . ' '
                    . $turno->trnd_hora_inicio;

                $fin =
                    $fechaTexto
                    . ' '
                    . $turno->trnd_hora_fin;

                $docente =
                    $turno->trnd_docidnumber;

                $nombre =
                    trim(
                        $turno->name
                            . ' '
                            . $turno->lastname
                    );

                $imagen =
                    url('/thumbnails/'.$turno->image);

                $color =
                    $coloresDocentes[$docente];

                $eventos[] = [

                    /*
                     * ID REAL del turno.
                     */
                    'id' =>
                    $turno->id,

                    /*
                     * Información que muestra
                     * FullCalendar.
                     */
                    'title' =>
                    $nombre,

                    'start' =>
                    $inicio,

                    'end' =>
                    $fin,

                    'backgroundColor' =>
                    $color,

                    'borderColor' =>
                    $color,

                    'textColor' =>
                    '#ffffff',

                    /*
                     * Información adicional.
                     * La podremos utilizar al hacer
                     * click sobre el evento.
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
                ];
            }
        }

        return $eventos;
    }
}
