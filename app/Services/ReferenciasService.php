<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ReferenciasService {

    public function getEstadosForExpediente();
    public function getRamasDerechoForExpediente();
    public function getTipoProcesoForExpediente();
    public function getReferenciasByFilter($filter);
}
