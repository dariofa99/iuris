<?php
namespace App\Repositories;

use App\Conciliacion;
use App\Services\ConciliacionesService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class ConciliacionesRepository extends BaseRepository implements ConciliacionesService{
   
    public function __construct(Conciliacion $estado)
    {       
        parent::__construct($estado);
    }
    public function store(Request $request): Conciliacion
    {
        $conciliacion = Conciliacion::create([
            'token'=>$request->has('token') ? $request->get('token') : str_replace("/", "", bcrypt(Str::random(5))),
            'num_conciliacion'=> $request->has('num_conciliacion') ? $request->get('num_conciliacion') : strtoupper("CCEAH-0-00-00") ,//"CCEAH-0-00-00",
            'num_solicitud'=> $request->has('num_solicitud') ? $request->get('num_conciliacion') : strtoupper("CCEAH-".Str::random(7)) ,//"CCEAH-0-00-00"
            'categoria_id'=> $request->has('categoria_id') ? $request->get('categoria_id') : 173,
            'estado_id'=>$request->has('estado_id') ? $request->get('estado_id') : 174,
            'periodo_id'=> $request->has('periodo_id') ? $request->get('periodo_id') : 1,
            'user_id'=>$request->has('user_id') ? $request->get('user_id') : auth()->user()->id
        ]);
        if(session()->has('sede')){
            $conciliacion->sedes()->attach(session('sede')->id_sede);
        }
        //autor
        $conciliacion->usuarios()->attach(auth()->user()->id,[
            'tipo_usuario_id'=> $request->has('tipo_usuario_id') ? $request->get('tipo_usuario_id') : 199,
            'estado_id'=>1
        ]);
        return $conciliacion;
    }
    public function getAllConciliaciones($request,$filtro = null, 
    $perPage = 10):LengthAwarePaginator
    {
        
            $query = Conciliacion::filter($request);
            $query = $this->applyFiltro($query);
            
    
            return $this->paginate($query, $perPage);
        }
    
        protected function applyFiltro($query)
        {
            return $query->where(function($query){
                if(!currentUser()->can('ver_all_conciliaciones')){
                    return $query->whereHas('usuarios',function($query1){
                        $query1->where([
                            'user_id'=>auth()->user()->id,
                    ]);                    
                });
                }       
            })->whereHas('sedes',function($query1){
                $query1->where([
                    'sede_id'=>session('sede')->id_sede,
            ]);                    
        });
        }
    
        protected function paginate($query, $perPage)
        {
            $page = Paginator::resolveCurrentPage('page');
            $results = $query->paginate($perPage, ['*'], 'page', $page);    
            $results->appends(request()->except('page'));    
            return $results;
        }

    

      /*   $conciliaciones = Conciliacion::filter($request)
        ->where(function($query){
            if(!currentUser()->can('ver_all_conciliaciones')){
                return $query->whereHas('usuarios',function($query1){
                    $query1->where([
                        'user_id'=>auth()->user()->id,
                ]);
                
                });
            }       
        })
        ->orderBy('conciliaciones.created_at','desc')->paginate(10); */
     
        //return $conciliaciones;
    }
  





?>