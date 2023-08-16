<?php

namespace App\Services;

use App\Periodo;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface PeriodosService {

    public function getPeriodoActivo():?Periodo;
    public function index(Request $request);
    public function store(Request $request):Periodo;
    public function update(Periodo $periodo,Request $request):Periodo;
   
}
?>