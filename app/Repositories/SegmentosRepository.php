<?php
namespace App\Repositories;

use App\Periodo;
use App\Segmento;
use App\Services\PeriodosService;
use App\Services\SegmentosService;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SegmentosRepository extends BaseRepository implements SegmentosService{
   
    public function __construct(Segmento $segmento)
    {
        parent::__construct($segmento);
    }
    public function getSegmentoActivo(): ?Segmento
    {
      $segmento = Segmento::where('estado', true)
        ->join('sede_segmentos as sg', 'sg.segmento_id', '=', 'segmentos.id')
        ->where('sg.sede_id', session('sede')->id_sede)
        ->first();
        return $segmento;
    }
  
}




?>