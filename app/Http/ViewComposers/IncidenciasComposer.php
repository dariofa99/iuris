<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\RamaDerecho;
use App\Cptonota;
use App\Segmento;
use App\MotivoEstadoCaso;
use App\Estado;
use App\ReqAsistencia;
use App\Periodo;
use App\RefAsignacionCaso;
use App\MotivoAsigCaso;
use App\ReferencesData;
use App\Services\PeriodosService;
use App\Services\ReferenciasService;
use App\Services\SegmentosService;
use DB;
use Illuminate\Support\Facades\App;

/**
 * 
 */
class IncidenciasComposer
{

	private $referenciasService;
	private $segmentosService;
	private $periodosService;

	public function __construct()
	{

		$this->referenciasService = App::make(ReferenciasService::class);
		//$this->segmentosService = App::make(SegmentosService::class);
		//$this->periodosService = App::make(PeriodosService::class);
	}


	public function compose(View $view)
	{



		$categorias_incidencia_exp = $this->referenciasService->getReferenciasByFilter(
			[
				'tabla_ref' => 'incidencias',
				'categoria' => 'type_category_exp'
			]
		);

		$categorias_incidencia_system = $this->referenciasService->getReferenciasByFilter(
			[
				'tabla_ref' => 'incidencias',
				'categoria' => 'type_category_system'
			]
		);

		$estados_incidencia_ = $this->referenciasService->getReferenciasByFilter(
			[
				'tabla_ref' => 'incidencias',
				'categoria' => 'type_status'
			]
		);




		$view->with([
			'categorias_incidencia'    => $categorias_incidencia_exp,
			'categorias_incidencia_system'    => $categorias_incidencia_system,		
			'estados_incidencia'    => $estados_incidencia_
		]);
	}
}
