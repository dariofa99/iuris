<?php

namespace App\Repositories;

use App\Estado;
use App\RamaDerecho;
use App\RefTipoProceso;
use App\Services\ReferenciasService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class ReferenciasRepository extends BaseRepository implements ReferenciasService
{

    private $validaSede;
    private $table;

    public function __construct()
    {
        
    }

    function getEstadosForExpediente()
    {
        $this->query = new Estado();
        $ramas =  $this->all();
        return $ramas ; 
    }
    public function getRamasDerechoForExpediente(){
        $this->query = new RamaDerecho();
        $ramas =  $this->getWithFilter(['categoria'=>'expedientes']);
        return $ramas ;
    }

    public function getTipoProcesoForExpediente(){
        $this->query = new RefTipoProceso();
        $ramas =  $this->all();
        return $ramas ;
    }
    
}
