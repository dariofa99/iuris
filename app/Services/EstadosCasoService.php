<?php

namespace App\Services;

use App\EstadoCaso;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface EstadosCasoService {

    public function store(Request $request):EstadoCaso;
   
}
?>