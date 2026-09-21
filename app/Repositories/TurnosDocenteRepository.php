<?php

namespace App\Repositories;


use App\TurnosDocente as AppTurnosDocente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TurnosDocenteRepository
{

    public function obtenerPorPeriodo($periodoId)
    {
        $turnos = AppTurnosDocente::join(
            'users',
            'turnos_docentes.trnd_docidnumber',
            '=',
            'users.idnumber'
        )
            ->select(
                'turnos_docentes.id',
                'turnos_docentes.trnd_docidnumber',
                'turnos_docentes.trnd_dia',
                'turnos_docentes.trnd_hora_inicio',
                'turnos_docentes.trnd_hora_fin',

                'users.name',
                'users.lastname',
                'users.image'
            )
            ->where(function ($query) {

                if (request()->has("docente_id") and request()->get("docente_id") != "") {
                    $query->where("turnos_docentes.trnd_docidnumber", request()->get("docente_id"));
                }
            })
            ->where(
                'turnos_docentes.trndid_periodo',
                $periodoId
            )
            ->orderBy(
                'turnos_docentes.trnd_docidnumber',
                'DESC'
            )
            ->get();


        // =====================================================
        // IDS DE LOS TURNOS
        // =====================================================

        $turnoIds = $turnos->pluck('id');


        // =====================================================
        // ASISTENCIAS Y REPOSICIONES
        // UNA SOLA CONSULTA PARA TODOS LOS TURNOS
        // =====================================================

        $asistencias = DB::table('asistencia_docentes')
            ->leftJoin(
                'referencias_tablas',
                'asistencia_docentes.tipo_asis',
                '=',
                'referencias_tablas.id'
            )
            ->whereIn(
                'asistencia_docentes.turno_docente_id',
                $turnoIds
            )
             ->where(function ($query) {

                if (request()->has("docente_id") and request()->get("docente_id") != "") {
                    $query->where("asistencia_docentes.docidnumber", request()->get("docente_id"));
                }
            })
            ->select(
                'asistencia_docentes.id',
                'asistencia_docentes.turno_docente_id',
                'asistencia_docentes.tipo_asis',
                'asistencia_docentes.categoria',
                'asistencia_docentes.descripcion',

                'asistencia_docentes.inicio as hora_inicio_asis',
                'asistencia_docentes.fin as hora_fin_asis',

                'referencias_tablas.ref_nombre as tipo_asis_nombre',
                'referencias_tablas.color as tipo_asis_color'
            )
            ->orderBy(
                'asistencia_docentes.inicio',
                'ASC'
            )
            ->get()
            ->groupBy('turno_docente_id');


        // =====================================================
        // ARMAR LA ESTRUCTURA DEL TURNO
        // =====================================================

        $turnos->each(function ($turno) use ($asistencias) {

            $registros = $asistencias->get(
                $turno->id,
                collect()
            );


            // =================================================
            // ASISTENCIAS DEL TURNO
            // AGRUPADAS POR FECHA
            // =================================================

            $turno->asistencias = $registros
                ->where('categoria', 'turno')
                ->groupBy(function ($registro) {

                    return Carbon::parse(
                        $registro->hora_inicio_asis
                    )->format('Y-m-d');
                });


            // =================================================
            // REPOSICIONES
            // =================================================

            $turno->reposiciones = $registros
                ->where('categoria', 'reposicion')
                ->values();
        });


        return $turnos;
    }
}
