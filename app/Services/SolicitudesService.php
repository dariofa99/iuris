<?php

namespace App\Services;

use App\Solicitud;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SolicitudesService {
    public function find(int $id);
    public function store(Request $request):Solicitud;
    public function getTurno();   
}


?>