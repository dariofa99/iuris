<?php

namespace App\Repositories;

use App\ExpedientePausas;
use App\Services\PausasService;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class PausasRepository extends BaseRepository implements PausasService
{

	public function __construct(ExpedientePausas $model)
	{
		parent::__construct($model);
	} 

	public function getByAsignacion($asignacion, $request)
	{

		$pausas = $asignacion->pausas()
			
			->where(function ($query) use ($request) {
				if (isset($request[0])) {
					$query->whereDate('fecha_inicial', $request[0]['operador'], $request[0]['value']);
				}
				if (isset($request[1])) {
					$query->whereDate('fecha_final', $request[1]['operador'], $request[1]['value']);
				}
			})


			->orderBy('fecha_inicial', 'asc')
			->get();
		return $pausas;
	}

	public function getDays($_vacaciones)
	{

		if (count($_vacaciones) > 0) {
			$days_vac = 0;
			foreach ($_vacaciones as $key => $vacaciones) {
				$fecha_vaca_in = Carbon::parse($vacaciones->fecha_inicial);
				$fecha_vaca_fin = Carbon::parse($vacaciones->fecha_final);
				$days_vac = $days_vac + getDiffDays($fecha_vaca_in, $fecha_vaca_fin);// intval($fecha_vaca_in->diffInDays($fecha_vaca_fin, false));
			}
			return ($days_vac);
		}

		return 0;
	}
}
