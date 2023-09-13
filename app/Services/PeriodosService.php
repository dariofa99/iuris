<?php

namespace App\Services;

use App\Periodo;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PeriodosService {
    public function find(int $id);
    public function getPeriodoActivo():?Periodo;
    public function index(Request $request);
    public function store(Request $request):Periodo;
    public function update(Periodo $periodo,Request $request):Periodo;
    public function findWithFilter(Array $filter):?Model;
}
?>