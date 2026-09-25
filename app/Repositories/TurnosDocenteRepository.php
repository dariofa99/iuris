<?php

namespace App\Repositories;

use App\AsistenciaDocentes;
use Illuminate\Http\Request;
use App\TurnosDocente as AppTurnosDocente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\TurnosDocente;
use Illuminate\Support\Facades\Log;

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

        $asistencias = AsistenciaDocentes::with('reposiciones')
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

            /*  $turno->asistencias->each(function ($asistencia) use ($registros) {

                $reposiciones = $registros
                    ->where('categoria', 'reposicion')
                    ->where('tipo_asis', 285)
                    ->where('turno_docente_id', $asistencia->first()->turno_docente_id);

                $asistencia->reposiciones = $reposiciones;
             }); */
        });


        return $turnos;
    }


    public function registrarAsistencia(Request $request)
    {
        $turno_docente = TurnosDocente::find($request->turno_id);

        if ($turno_docente === null) {
            return response()->json(['error' => 'El docente no coincide con el turno seleccionado.'], 400);
        }


        $inicio = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->fecha_turno . ' ' . ($request->asistencia_id != null ? $request->hora_inicio : $request->hora_inicio)
        )->format('Y-m-d H:i:s');

        $fin = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->fecha_turno . ' ' . ($request->asistencia_id != null ? $request->hora_fin : $request->hora_fin)
        )->format('Y-m-d H:i:s');

        if ($request->tipo_asis == 284) {
            $minutos_reponer = Carbon::parse($turno_docente->trnd_hora_inicio)->diffInMinutes(Carbon::parse($turno_docente->trnd_hora_fin));
            $inicio = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_turno . ' ' . ($turno_docente->trnd_hora_inicio)
            )->format('Y-m-d H:i:s');

            $fin = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_turno . ' ' . ($turno_docente->trnd_hora_fin)
            )->format('Y-m-d H:i:s');
        } else {
            $minutos_de_atencion = Carbon::parse($inicio)->diffInMinutes(Carbon::parse($fin));
            $minutos_turno = Carbon::parse($turno_docente->trnd_hora_inicio)->diffInMinutes(Carbon::parse($turno_docente->trnd_hora_fin));

            $minutos_reponer = $minutos_turno - $minutos_de_atencion;
        }



        $asistencia = AsistenciaDocentes::create([
            'docidnumber' => $turno_docente->trnd_docidnumber,
            'tipo_asis' => $request->tipo_asis,
            'inicio' => $inicio,
            'fin' => $fin,
            'descripcion' => $request->descripregisdocasis == '' ? "Sin descripción" : $request->descripregisdocasis,
            'categoria' => $request->categoria ?? "turno",
            'turno_docente_id' => $turno_docente->id,
            'minutos_reponer' => $minutos_reponer
        ]);

        if ($request->has('fecha_repo') && $request->has('hora_inicio_repo') && $request->has('hora_fin_repo')) {
            $inicio_repo = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_repo . ' ' . $request->hora_inicio_repo . ":00"
            )->format('Y-m-d H:i:s');

            $fin_repo = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_repo . ' ' . $request->hora_fin_repo . ":00"
            )->format('Y-m-d H:i:s');

            $reposicion = AsistenciaDocentes::create([
                'docidnumber' => $turno_docente->trnd_docidnumber,
                'tipo_asis' => 285,
                'inicio' => $inicio_repo,
                'fin' => $fin_repo,
                'descripcion' => $request->descripregisdocasis,
                'categoria' => $request->categoria ?? "reposicion",
                'turno_docente_id' => $turno_docente->id,
                // 'minutos_reponer' => $request->minutos_reponer
            ]);

            Log::info("Asistencia ID: " . $asistencia->id . " - Reposición ID: " . $reposicion->id);
            $asistencia->reposiciones()->attach($reposicion->id);
        }

        return $asistencia;
    }

     public function actualizarAsistencia(Request $request)
    {
        $turno_docente = TurnosDocente::find($request->turno_id);

        try {
            $asistencia = AsistenciaDocentes::find($request->asistencia_id);
            if (!$asistencia) {
                return response()->json(['error' => 'Asistencia no encontrada.'], 404);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al buscar la asistencia: ' . $e->getMessage()], 500);
        }


        $inicio = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->fecha_turno . ' ' . ($request->asistencia_id != null ? $request->hora_inicio : $request->hora_inicio)
        )->format('Y-m-d H:i:s');

        $fin = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $request->fecha_turno . ' ' . ($request->asistencia_id != null ? $request->hora_fin : $request->hora_fin)
        )->format('Y-m-d H:i:s');

        if ($request->tipo_asis == 284) {
            $minutos_reponer = Carbon::parse($turno_docente->trnd_hora_inicio)->diffInMinutes(Carbon::parse($turno_docente->trnd_hora_fin));
            $inicio = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_turno . ' ' . ($turno_docente->trnd_hora_inicio)
            )->format('Y-m-d H:i:s');

            $fin = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_turno . ' ' . ($turno_docente->trnd_hora_fin)
            )->format('Y-m-d H:i:s');
        } else {
            $minutos_de_atencion = Carbon::parse($inicio)->diffInMinutes(Carbon::parse($fin));
            $minutos_turno = Carbon::parse($turno_docente->trnd_hora_inicio)->diffInMinutes(Carbon::parse($turno_docente->trnd_hora_fin));

            $minutos_reponer = $minutos_turno - $minutos_de_atencion;
        }



        //$asistencia->docidnumber = $turno_docente->trnd_docidnumber;
        $asistencia->tipo_asis = $request->tipo_asis;
        $asistencia->inicio = $inicio;
        $asistencia->fin = $fin;
        $asistencia->descripcion = $request->descripregisdocasis == '' ? "Sin descripción" : $request->descripregisdocasis;
        //$asistencia->categoria = $request->categoria ?? "turno";
        //$asistencia->turno_docente_id = $turno_docente->id;
        $asistencia->minutos_reponer = 0;
        $asistencia->save();



        if ($request->has('fecha_repo') && $request->has('hora_inicio_repo') && $request->has('hora_fin_repo')) {
            $inicio_repo = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_repo . ' ' . $request->hora_inicio_repo . ":00"
            )->format('Y-m-d H:i:s');

            $fin_repo = Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $request->fecha_repo . ' ' . $request->hora_fin_repo . ":00"
            )->format('Y-m-d H:i:s');

            $reposicion = AsistenciaDocentes::create([
                'docidnumber' => $turno_docente->trnd_docidnumber,
                'tipo_asis' => 285,
                'inicio' => $inicio_repo,
                'fin' => $fin_repo,
                'descripcion' => $request->descripregisdocasis,
                'categoria' => $request->categoria ?? "reposicion",
                'turno_docente_id' => $turno_docente->id,
                // 'minutos_reponer' => $request->minutos_reponer
            ]);
            $asistencia->reposiciones()->attach($reposicion->id);
        }

        return $asistencia;
    }
}
