<?php
namespace App\Repositories;

use App\Nota;

use Illuminate\Http\Request;


class NotasGenRepository extends BaseRepository {
   
    public function __construct(Nota $model)
    {
        parent::__construct($model);
    }

    public function store(Request $request){
      
        $turnos = $this->model->whereHas('estudiante',function($query){
            return $query->whereHas('sedes',function($query){
                    $query->where('sede_id',session('sede')->id_sede);
            });
        })->orderBy('turnos.trnid_color','desc')->get();
        return $turnos;
    }

     public function obtenerPorDocenteYPeriodo(
        $docidnumber,
        $expedientes,
        $inicio,
        $fin
    ) {
        return Nota::query()
            ->where('docidnumber', $docidnumber)
            ->whereIn('expidnumber', $expedientes)
            ->whereBetween('created_at', [$inicio, $fin])
            ->with('concepto')
            ->orderBy('created_at')
            ->get();
    }

}
