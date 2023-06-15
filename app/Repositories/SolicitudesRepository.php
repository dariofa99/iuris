<?php
namespace App\Repositories;

use App\Mail\ConfirmarCorreo;
use App\Sede;
use App\Services\SolicitudesService;
use Illuminate\Http\Request;
use App\Solicitud;

class SolicitudesRepository extends BaseRepository implements SolicitudesService {
   
    public function __construct(Solicitud $solicitud)
    {
        parent::__construct($solicitud);
    }

    function store($request):Solicitud{
     
     $solicitud = Solicitud::create([
        'tipodoc_id' => $request->has('tipodoc_id') ? $request['tipodoc_id'] : 1, 
        'number' => $request->has('number') ? $request['number'] : 0,
        'idnumber' => $request->has('idnumber') ? $request['idnumber'] : 1, 
        'tipopers_id' => $request->has('tipopers_id') ? $request['tipopers_id'] : 237, 
        'name' => $request['name'],
        'lastname' => $request['lastname'],
        'email' => $request['email'],
        'tel1' =>  $request->has('tel1') ? $request['tel1'] : '',
        'token' =>  $request->has('token') ? $request['token'] : str_replace ('/','', bcrypt(time())),
        'description' =>  $request->has('description') ? $request['description'] : '',
        'turno' =>  $request->has('turno') ? $request['turno'] : 0,
        'mensaje' =>  $request->has('mensaje') ? $request['mensaje'] : '',
        'tiempo_espera' =>  $request->has('tiempo_espera') ? $request['tiempo_espera'] : null,
        'type_status_id' =>  $request->has('type_status_id') ? $request['type_status_id'] : 154,
        'type_category_id' =>  $request->has('type_category_id') ? $request['type_category_id'] :153,
        'estrato_id' =>  $request->has('estrato_id') ? $request['estrato_id'] : 1      
     ]);
     $pref = "0".$solicitud->id;
     $pref = substr($pref,-2);
     $solicitud->number = $request->idnumber.'-'. $pref;
     $solicitud->save();
     if($request->has('sede_id')){
        $sede = Sede::find($request->get('sede_id'));
        session(["sede"=>$sede]);
        $solicitud->sedes()->attach(session('sede')->id_sede);
      }else{
        if(session()->has('sede')){
          $solicitud->sedes()->attach(session('sede')->id_sede);
        }
      } 
      return $solicitud;
}

function getTurno()
{
    $solicitud = $this->findWithFilter(['created_at'=>date('Y-m-d')]);
    $turno = 1;
    if($solicitud)$turno = $solicitud->turno + 1;
    return $turno;
}

}




?>