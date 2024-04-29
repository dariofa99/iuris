<?php

namespace App\Services;

use App\Segmento;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface SegmentosService {

    public function getSegmentoActivo():?Segmento;
    public function getSegmentoAsignacion($asignacion):?Segmento;
    public function getSegmentoEvaluacion($asignacion):?Segmento;
}
?>