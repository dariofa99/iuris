<?php

namespace App\Services;

use App\Conciliacion;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ConciliacionesService {

    public function find(int $id);
    public function store(Request $request):Conciliacion;
    public function getAllConciliaciones(Request $request):LengthAwarePaginator;
   
}
?>