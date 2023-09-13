<?php

namespace App\Services;

use App\ConciliacionComentario;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ConciliacionComentarioService {

   /*  public function find(int $id);
      public function getAllConciliaciones(Request $request):LengthAwarePaginator;
    public function addUser(Conciliacion $conciliacion,Request $request):Conciliacion;
    */

    public function store(Request $request):ConciliacionComentario;
  
}
?>