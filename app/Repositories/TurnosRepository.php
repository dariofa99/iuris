<?php

namespace App\Repositories;

use App\Periodo;
use App\Services\PeriodosService;
use App\Services\TurnosService;
use App\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurnosRepository extends BaseRepository implements TurnosService
{

    private $periodosService;
    public function __construct(Turno $model)
    {
        parent::__construct($model);
        $this->periodosService = app()->make(PeriodosService::class);

    }

    public function index(Request $request)
    {

        $turnos = $this->model->search($request)->whereHas('estudiante', function ($query) {
                return $query->whereHas('sedes', function ($query) {
                    $query->where('sede_id', session('sede')->id_sede);
                });
            })->orderBy('turnos.trnid_color', 'desc')->get();
        return $turnos;
    }

    public function getAsistencia(Request $request)
    {
        $periodo =  $this->periodosService->getPeriodoActivo();

        $fecha = $periodo->prdfecha_inicio;

        $rasistencia = DB::table('users')
            ->leftjoin('asistencia',  'users.idnumber', '=', 'asistencia.astid_estudent')
            ->join('referencias_tablas as ref', 'ref.id', '=', 'users.cursando_id')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('sede_usuarios', 'sede_usuarios.user_id', '=', 'users.id')
            ->leftjoin('sedes', 'sedes.id_sede', '=', 'sede_usuarios.sede_id')
            ->select(
                'users.name',
                'users.lastname',
                'ref.ref_nombre',
                'users.idnumber',

                /* =========================
       ASISTENCIAS
    ========================== */
                DB::raw('
        SUM(IF(asistencia.astid_tip_asist IN (121,127,128),1,0)) AS asistencia
    '),



                /* =========================
       FALTAS
    ========================== */
                DB::raw('
        SUM(IF(asistencia.astid_tip_asist = 122,1,0)) AS falta_simple
    '),

                DB::raw('
        SUM(IF(asistencia.astid_tip_asist IN (123,126),1,0)) AS falta_doble
    '),

                /* =========================
       REPOSICIONES
    ========================== */
                DB::raw('
        SUM(IF(asistencia.astid_tip_asist = 125,1,0)) AS reposicion
    '),



                /* =========================
       total ASISTENCIAS
    ========================== */
                DB::raw('
        SUM(IF(asistencia.astid_tip_asist IN (121,127,128,125),1,0)) AS total_asistencias
    '),
                /* =========================
       FALTAS REALES (RESTANDO REPOSICIÓN)
       FS + (FD*2) - R
    ========================== */
                DB::raw('
        GREATEST(
            0,
            (
                SUM(IF(asistencia.astid_tip_asist = 122,1,0))
                +
                (SUM(IF(asistencia.astid_tip_asist IN (123,126),1,0)) * 2)
                
            )
        ) AS total_faltas
    '),

                /* =========================
       DÍAS QUE DEBÍA ASISTIR
       A + faltas reales
    ========================== */
                DB::raw('
        (
            SUM(IF(asistencia.astid_tip_asist IN (121,127,128),1,0))
            +
            GREATEST(
                0,
                (
                    SUM(IF(asistencia.astid_tip_asist = 122,1,0))
                    +
                    (SUM(IF(asistencia.astid_tip_asist IN (123,126),1,0)) * 2)
                    
                )
            )
        ) AS dias_debio_asistir
    '),

                /* =========================
       NOTA PROPORCIONAL
       total_asistencias * (total_asistencias / dias_debio_asistir)
    ========================== */
                DB::raw('
        ROUND(

        SUM(IF(asistencia.astid_tip_asist IN (121,127,128,125),1,0)) *
        (
            5
            /
            (
                SUM(IF(asistencia.astid_tip_asist IN (121,127,128),1,0))
                +
                GREATEST(
                    0,
                    (
                        SUM(IF(asistencia.astid_tip_asist = 122,1,0))
                        +
                        (SUM(IF(asistencia.astid_tip_asist IN (123,126),1,0)) * 2)
                        
                    )
                )
            )
        )
        
        
        
        ,2) AS nota_proporcional
                ')

            )

             ->where(function ($query) use ($request) {
                if ($request->has('name') and $request->input('name') != '') {
                    return $query->orWhere('users.lastname', 'like', "%{$request->name}%")
                        ->orWhere('users.name', 'like', "%{$request->name}%");
                }
                if ($request->has('idnumber') and $request->input('idnumber') != '') {
                    return $query->orWhere('users.idnumber', $request->idnumber);
                }
            })
            ->where('users.active', true)
            ->where('role_id', '6')
            ->where('astfecha', '>=', $fecha)
            ->where('sedes.id_sede', session('sede')->id_sede)
            ->groupBy('users.idnumber')
            ->get();

        return $rasistencia;
    }
}
