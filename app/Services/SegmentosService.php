<?php

namespace App\Services;

use App\Segmento;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface SegmentosService {

    public function getSegmentoActivo():Segmento;
   
}
?>