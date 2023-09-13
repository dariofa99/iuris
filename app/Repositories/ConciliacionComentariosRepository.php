<?php

namespace App\Repositories;

use App\Conciliacion;
use App\ConciliacionComentario;
use App\Services\ConciliacionComentarioService;
use App\Services\ConciliacionesService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class ConciliacionComentariosRepository extends BaseRepository implements ConciliacionComentarioService
{

    public function __construct(ConciliacionComentario $estado)
    {
        parent::__construct($estado);
    }
    public function store(Request $request): ConciliacionComentario
    {
        $conciliacion = ConciliacionComentario::create([
            'comentario'=> $request->input('cuerpo_correo'),
            'user_id' => Auth::user()->id,
            'asunto'=>$request->input('asunto'),
            'reporte_id'=>$request->input('reporte_id'), 
            'conciliacion_id'=>$request->input('conciliacion_id')
        ]);
      
        return $conciliacion;
    }

}
