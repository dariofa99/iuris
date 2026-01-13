<?php

namespace App\Repositories;

use App\Services\PeriodosService;
use App\Services\TurnosService;
use App\Services\VacacionesService;
use App\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class VacacionesRepository extends BaseRepository implements VacacionesService
{

    protected PeriodosService $periodoService;
    public function __construct(Turno $model)
    {
        parent::__construct($model);
        $this->periodoService = App::make(PeriodosService::class);
    }

    public function getByDates(array $request)
    {

        $_vacaciones = DB::table("vacaciones_periodo")
            /*  ->whereDate('fecha_inicio',$request[0]['operador'], $request[0]['value'])
        ->whereDate('fecha_fin',$request[1]['operador'], $request[1]['value'])
       */
            ->where("periodo_id", $this->periodoService->getPeriodoActivo()->id)
            ->where(function ($query) use ($request) {
                if (isset($request[0])) {
                    $query->whereDate('fecha_inicio', $request[0]['operador'], $request[0]['value']);
                }
                if (isset($request[1])) {
                    $query->whereDate('fecha_fin', $request[1]['operador'], $request[1]['value']);
                }
            })
            ->get();
        return $_vacaciones;
    }

    public function getDays($_vacaciones)
    {

        if (count($_vacaciones) > 0) {
            $days_vac = 0;
            foreach ($_vacaciones as $key => $vacaciones) {
                $fecha_vaca_in = Carbon::parse($vacaciones->fecha_inicio);
                $fecha_vaca_fin = Carbon::parse($vacaciones->fecha_fin);
                $days_vac = $days_vac + intval($fecha_vaca_in->diffInDays($fecha_vaca_fin, false));
            }
            return ($days_vac);
        }

        return 0;
    }
}
