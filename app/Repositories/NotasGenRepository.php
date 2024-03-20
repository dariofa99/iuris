<?php
namespace App\Repositories;

use App\NotaGen;
use App\Services\TurnosService;
use App\Turno;
use Illuminate\Http\Request;


class NotasGenRepository extends BaseRepository implements TurnosService {
   
    public function __construct(NotaGen $model)
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
}
