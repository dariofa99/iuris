<?php
namespace App\Repositories;

use App\Requerimiento;
use App\Services\RequerimientosService;
use Illuminate\Http\Request;


class RequerimientosRepository extends BaseRepository implements RequerimientosService {
   
    public function __construct(Requerimiento $req)
    {
        parent::__construct($req);
    }

    public function index(Request $request){
      
        $requerimientos = $this->model::orderBy('reqfecha', 'asc')
                /* ->join('ref_reqasis', 'ref_reqasis.reqid_refasis', '=', 'requerimientos.reqid_asistencia')
                ->join('expedientes', 'expedientes.expid', '=', 'requerimientos.reqexpid')
                ->join('sede_expedientes as se', 'se.expediente_id', '=', 'expedientes.id') */
               
               ->Criterio($request)
              /*   ->select(
                    'requerimientos.id',
                    'requerimientos.created_at',
                    'requerimientos.reqmotivo',
                    'expedientes.expid',
                    'requerimientos.reqfecha',
                    'requerimientos.reqhora',
                    'ref_reqasis.ref_reqasistencia',
                    'reqentregado',
                    'evaluado'
                ) */
                ->paginate(10);
        
        return $requerimientos;
    }
}
