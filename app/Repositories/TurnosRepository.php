<?php
namespace App\Repositories;


use App\Services\TurnosService;
use App\Turno;
use Illuminate\Http\Request;


class TurnosRepository extends BaseRepository implements TurnosService {
   
    public function __construct(Turno $model)
    {
        parent::__construct($model);
    }

    public function index(Request $request){
      
        $turnos = $this->model->whereHas('estudiante',function($query){
            return $query->whereHas('sedes',function($query){
                    $query->where('sede_id',session('sede')->id_sede);
            });
        })->get();
        return $turnos;
    }
}
