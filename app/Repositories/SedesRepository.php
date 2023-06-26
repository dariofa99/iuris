<?php
namespace App\Repositories;

use App\Sede;
use App\Services\SedesService;
use Illuminate\Http\Request;


class SedesRepository extends BaseRepository implements SedesService {
   
    public function __construct(Sede $sede)
    {
        parent::__construct($sede);
    }

    public function setSede(Request $request):?Sede{
      
        if($request->has('sede_id')){
            $sede = Sede::find($request->get('sede_id'));
            session(["sede"=>$sede]);           
        }
        return $sede;
    }
}
