<?php

namespace App\Repositories;

use App\AsigDocenteCaso;
use App\Expediente;
use App\Services\AsignacionDocenteCasosService;
use App\Services\ExpedientesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class AsignacionDocenteCasosRepository extends BaseRepository implements AsignacionDocenteCasosService
{

    public function __construct(AsigDocenteCaso $model)
    {
        parent::__construct($model);
    }
    public function store(Request $request): AsigDocenteCaso
    {

        $this->model->docidnumber = $request->input('docidnumber');
        $this->model->activo = ($request->has('activo')) ? $request->input('activo') : 1;
        $this->model->cambio_docidnumber = $request->has('cambio_docidnumber') ? $request->input('cambio_docidnumber') : null;
        $this->model->asig_caso_id = $request->input('asig_caso_id');
        $this->model->user_created_id = auth()->user()->idnumber;;
        $this->model->user_updated_id = auth()->user()->idnumber;;
        $this->model->save();

        return $this->model;
    }

    public function update(AsigDocenteCaso $expediente, Request $request): AsigDocenteCaso
    {
        $expediente->fill($request->all());
        $expediente->user_updated_id = auth()->user()->idnumber;
        $expediente->save();
        return $expediente;
    }

    public function obtenerExpedientesActivosPorDocente($docidnumber)
    {
        return AsigDocenteCaso::query()
            ->join('asignacion_caso as asig', 'asignacion_docente_caso.asig_caso_id', '=', 'asig.id')
            ->join('expedientes as exp', 'asig.asigexp_id', '=', 'exp.expid')
            ->whereNotIn('exp.expestado_id', [7, 8])
            ->where('docidnumber', $docidnumber)
            ->where('asignacion_docente_caso.activo', 1)
            ->with('asignacionCaso')
            ->get()
            ->pluck('asignacionCaso.asigexp_id')
            ->filter()
            ->unique()
            ->values();
    }

    public function obtenerExpedientesConActividad(
        string $docidnumber,
        string $inicio,
        string $fin
    ) {
        $sql = "
            SELECT DISTINCT ac.asigexp_id
            FROM asignacion_docente_caso adc

            INNER JOIN asignacion_caso ac
                ON ac.id = adc.asig_caso_id

            WHERE adc.docidnumber = ?
              AND adc.activo = 1

              AND ac.asigexp_id IN (

                /* =====================================================
                   1. ACTUACIONES
                   ===================================================== */
                SELECT a.actexpid
                FROM actuacions a
                WHERE
                    (
                        a.actusercreated = ?
                        AND a.created_at BETWEEN ? AND ?
                    )
                    OR
                    (
                        a.actdocidnumber = ?
                        AND a.actdocenfechamod BETWEEN ? AND ?
                    )

                UNION

                /* =====================================================
                   2. EVALUACIONES / NOTAS
                   ===================================================== */
                SELECT n.expidnumber
                FROM notas n
                WHERE n.docidnumber = ?
                  AND n.created_at BETWEEN ? AND ?

                UNION

                /* =====================================================
                   3. ASESORÍAS
                   ===================================================== */
                SELECT ad.expidnumber
                FROM asesorias_docente ad
                WHERE ad.docidnumber = ?
                  AND ad.created_at BETWEEN ? AND ?

                UNION

                /* =====================================================
                   4. CITACIONES
                   ===================================================== */
                SELECT ac2.asigexp_id
                FROM citaciones_estudiante ce

                INNER JOIN asignacion_caso ac2
                    ON ac2.id = ce.asignacion_caso_id

                WHERE ce.docidnumber = ?
                  AND ce.created_at BETWEEN ? AND ?

                UNION

                /* =====================================================
                   5. CAMBIOS DE ESTADO / CIERRES
                   ===================================================== */
                SELECT ec.expidnumber
                FROM estados_caso ec
                WHERE ec.useridnumber = ?
                  AND ec.created_at BETWEEN ? AND ?
            )
        ";

        return collect(DB::select($sql, [
            $docidnumber,

            $docidnumber,
            $inicio,
            $fin,

            $docidnumber,
            $inicio,
            $fin,

            $docidnumber,
            $inicio,
            $fin,

            $docidnumber,
            $inicio,
            $fin,

            $docidnumber,
            $inicio,
            $fin,

            $docidnumber,
            $inicio,
            $fin,
        ]))->pluck('asigexp_id');
    }
}
