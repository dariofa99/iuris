<?php

namespace App\Services;

use App\Periodo;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface PeriodosService {

    public function getPeriodoActivo():Periodo;
   
}
?>