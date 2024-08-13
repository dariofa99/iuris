<?php
namespace App\Repositories;



use App\LogSession;
use App\Sede;
use App\Services\LoginService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginRepository extends BaseRepository implements LoginService {
   
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function login(Request $request){
        
        $response=[];
        if(
            ($request->has('clave') and 
            $request->has('user_name') and 
            Auth::attempt([$request['clave'] => $request['user_name'], 'password' => $request['password'] ])
            )          
            || !Auth::guest()
            ){
         
            if(count(Auth::user()->sedes)<=0){
                if(count(Sede::all())==1){
                    $sede = Sede::first();
                    Auth::user()->sedes()->attach($sede->id_sede);
                    session(["sede"=>$sede]);                  
                }elseif(count(Sede::all())>1){
                    if(Auth::user()->hasRole("solicitante")){
                        $solicitud=currentUser()->solicitudes()
                        ->whereIn('type_status_id',[162,165])->first();
                        if($solicitud){
                            $sede = $solicitud->sedes()->first();
                            if($sede){
                                Auth::user()->sedes()->attach($sede->id_sede);
                                session(["sede"=>$sede]);
                            }                           
                        }                      
                    }else{
                        return $response['redirect']="dashboard";
                    }
                   
                }              
            }elseif(count(Auth::user()->sedes)>=1){
               $sede =  Auth::user()->sedes()->first();            
                session(["sede"=>$sede]);               
            } 
            $response['login'] = true;           
            return $response;
        }
        $response['login'] = false;           
        return $response;
    }

 
}
