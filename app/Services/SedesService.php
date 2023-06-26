<?php

namespace App\Services;

use App\Sede;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SedesService {
    public function setSede(Request $request):?Sede;    
}


?>