<?php
namespace App\Repositories;

use App\EstadoCaso;
use App\Periodo;
use App\Segmento;
use App\Services\EstadosCasoService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EstadosCasoRepository extends BaseRepository implements EstadosCasoService{
   
    public function __construct(EstadoCaso $estado)
    {
        parent::__construct($estado);
    }
    public function store(Request $request): EstadoCaso
    {
      $estado = EstadoCaso::create([
        'comentario' => $request->has('comentario') ? $request['comentario'] : "Sin comentario",
        'useridnumber' => $request->has('useridnumber') ? $request['useridnumber'] : auth()->user()->idnumber, 
        'expidnumber' => $request['expidnumber'], 
        'ref_estado_id' => $request['ref_estado_id'],
        'ref_motivo_estado_id' => $request['ref_motivo_estado_id'],       
      ]);
        return $estado;
    }
  
}




?>