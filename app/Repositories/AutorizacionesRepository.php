<?php
namespace App\Repositories;

use App\Autorizacion;
use App\Services\AutorizacionesService;
use Illuminate\Http\Request;


class AutorizacionesRepository extends BaseRepository implements AutorizacionesService {
   
    public function __construct(Autorizacion $req)
    {
        parent::__construct($req);
       
    }

    public function index(Request $request){
       
             
        $this->applyValidateSede();     
        $this->query = $this->query
        ->whereHas('asignacion.expediente',function($query){
            $query->whereIn('expestado_id',[1,3,4]);
        })->search($request);  
        $autorizaciones = $this->query->orderBy('autorizaciones.created_at','desc')
        ->paginate(20);
        
        return $autorizaciones;
    }
}
