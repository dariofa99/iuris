<?php

namespace App\Services;

use App\Estado;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface EstadosService {

    public function all():Collection;
   
}
?>