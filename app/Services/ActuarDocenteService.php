<?php

namespace App\Services;


use App\Repositories\ActuacionRepository;
use App\Repositories\AsesoriaDocenteRepository;
use App\Repositories\AsignacionDocenteCasosRepository;
use App\Repositories\CitacionEstudianteRepository;
use App\Repositories\EstadosCasoRepository;
use App\Repositories\NotasGenRepository;

class ActuarDocenteService
{
    protected $asignacionRepository;
    protected $actuacionRepository;
    protected $notaRepository;
    protected $asesoriaRepository;
    protected $citacionRepository;
    protected $estadoRepository;

    public function __construct(
        AsignacionDocenteCasosRepository $asignacionRepository,
        ActuacionRepository $actuacionRepository,
        NotasGenRepository $notaRepository,
        AsesoriaDocenteRepository $asesoriaRepository,
        CitacionEstudianteRepository $citacionRepository,
        EstadosCasoRepository $estadoRepository
    ) {
        $this->asignacionRepository = $asignacionRepository;
        $this->actuacionRepository = $actuacionRepository;
        $this->notaRepository = $notaRepository;
        $this->asesoriaRepository = $asesoriaRepository;
        $this->citacionRepository = $citacionRepository;
        $this->estadoRepository = $estadoRepository;
    }

    public function consultar(
        $docidnumber,
        $fecha,
        $horaInicio,
        $horaFin 
    ) {
        $inicio = $fecha . ' ' . $horaInicio . '';
        $fin = $fecha . ' ' . $horaFin . '';

        /*
         * 1. Obtener expedientes actualmente asignados
         * al docente.
         */
        $expedientes = $this->asignacionRepository
            ->obtenerExpedientesConActividad($docidnumber, $inicio, $fin);

           // 
/*  dd(
    ($expedientes),
    $expedientes->isEmpty()
);  */
        if ($expedientes->isEmpty()) {
            return [
                'docente' => $docidnumber,
                'periodo' => [
                    'fecha' => $fecha,
                    'hora_inicio' => $horaInicio,
                    'hora_fin' => $horaFin,
                ],
                'expedientes' => collect()
            ];
        }

      

        /*
         * 2. Consultar todas las actividades
         */
        $actuaciones = $this->actuacionRepository
            ->obtenerPorDocenteYPeriodo(
                $docidnumber,
                $expedientes,
                $inicio,
                $fin
            );
  
        $notas = $this->notaRepository
            ->obtenerPorDocenteYPeriodo(
                $docidnumber,
                $expedientes,
                $inicio,
                $fin
            );
  


        $asesorias = $this->asesoriaRepository
            ->obtenerPorDocenteYPeriodo(
                $docidnumber,
                $expedientes,
                $inicio,
                $fin
            );


         //    dd($asesorias);


        $citaciones = $this->citacionRepository
            ->obtenerPorDocenteYPeriodo(
                $docidnumber,
                $expedientes,
                $inicio,
                $fin
            );

        $estados = $this->estadoRepository
            ->obtenerPorDocenteYPeriodo(
                $docidnumber,
                $expedientes,
                $inicio,
                $fin
            );

        /*
         * 3. Convertir todo en una colección
         * de actividades.
         */
        $actividades = collect();

        /*
         * ACTUACIONES
         */
        foreach ($actuaciones as $actuacion) {
            $actividades->push([
                'tipo' => 'actuacion',
                'expediente' => $actuacion->actexpid,
                'fecha' => $actuacion->created_at,
                'update_fecha' => $actuacion->updated_at,
                'datos' => $actuacion,
            ]);
        }

        /*
         * EVALUACIONES
         *
         * Aquí posteriormente agrupamos las 4 notas.
         */
        $evaluaciones = $this->agruparEvaluaciones($notas);

        foreach ($evaluaciones as $evaluacion) {
            $actividades->push([
                'tipo' => 'evaluacion',
                'expediente' => $evaluacion['expediente'],
                'fecha' => $evaluacion['fecha'],
                'update_fecha' => $evaluacion['update_fecha'],
                'datos' => $evaluacion,
            ]);
        }

        /*
         * ASESORÍAS
         */
        foreach ($asesorias as $asesoria) {
            $actividades->push([
                'tipo' => 'asesoria',
                'expediente' => $asesoria->expidnumber,
                'fecha' => $asesoria->created_at,
                'update_fecha' => $asesoria->updated_at,
                'datos' => $asesoria,
            ]);
        }

        /*
         * CITACIONES
         */
        foreach ($citaciones as $citacion) {
            $actividades->push([
                'tipo' => 'citacion',
                'expediente' => $citacion->asignacion->asigexp_id,
                'fecha' => $citacion->created_at,
                'update_fecha' => $citacion->updated_at,
                'datos' => $citacion,
            ]);
        }

        /*
         * CAMBIOS DE ESTADO
         */
        foreach ($estados as $estado) {
            $actividades->push([
                'tipo' => 'cambio_estado',
                'expediente' => $estado->expidnumber,
                'fecha' => $estado->created_at,
                'update_fecha' => $estado->updated_at,
                'datos' => $estado,
            ]);
        }

        /*
         * 4. Ordenar cronológicamente
         */
        $actividades = $actividades
            ->sortBy('fecha')
            ->values();

        /*
         * 5. Agrupar por expediente
         */
        $resultado = $actividades
            ->groupBy('expediente')
            ->map(function ($actividades, $expediente) {

                return [
                    'expediente' => $expediente,
                    'actividades' => $actividades
                        ->values()
                        ->toArray()
                ];
            })
            ->values();

         //   dd($resultado);
        return [
            'docente' => $docidnumber,
            'periodo' => [
                'fecha' => $fecha,
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
            ],
            'expedientes' => $resultado
        ];
    }

    private function agruparEvaluaciones($notas)
    {
        return $notas
            ->groupBy(function ($nota) {
                return $nota->expidnumber . '|' .
                       $nota->created_at;
            })
            ->map(function ($grupo) {

                $primera = $grupo->first();

                return [
                    'expediente' => $primera->expidnumber,
                    'fecha' => $primera->created_at,
                    'update_fecha' => $primera->updated_at,

                    'conocimiento' => $grupo
                        ->firstWhere('cptnotaid', 1)->nota,

                    'aplicacion' => $grupo
                        ->firstWhere('cptnotaid', 2)->nota,

                    'etica' => $grupo
                        ->firstWhere('cptnotaid', 3)->nota,

                    'concepto' => $grupo
                        ->firstWhere('cptnotaid', 4)->nota,
                ];
            })
            ->values();
    }
}