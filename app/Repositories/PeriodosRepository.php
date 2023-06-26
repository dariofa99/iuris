<?php
namespace App\Repositories;

use App\Periodo;
use App\Services\PeriodosService;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PeriodosRepository extends BaseRepository implements PeriodosService{
   
    public function __construct(Periodo $periodo)
    {
        parent::__construct($periodo);
    }
    public function getPeriodoActivo(): Periodo 
    {
        $periodo = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
        ->where('sp.sede_id', session('sede')->id_sede)
        ->where('estado', true)
        ->first();
        return $periodo;
    }
  
}




?>