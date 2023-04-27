<?php

namespace App\Http\Controllers;

use App\AudienciaConciliacion;
use App\Conciliacion;
use App\Periodo;
use App\SalasAlternasConciliacion;
use Illuminate\Http\Request;
use App\Solicitud;
use App\TablaReferencia;
use App\Turno;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Facades\App\Facades\NewPush;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(auth()->user()->expedientes);
        return view('front.index'); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \Auth::user();
        $user->unreadNotifications->markAsRead();
        return response()->json($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('$conciliaciones' )     ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function conciliaciones(Request $request)
    {
        $conciliaciones = Conciliacion::where(function($query){
            
                //$query->on('conciliaciones.id', '=', 'conciliacion_has_user.conciliacion_id');
            if(!currentUser()->can('ver_conciliaciones')){                
                return $query->where('user_id',currentUser()->id);
            }
        }) 
        
        ->orderBy('conciliaciones.created_at','desc')->paginate(10);  

      

               //dd($conciliaciones)     ;
        return view('front.conciliaciones.index',compact('conciliaciones'));
    }

    public function conciliaciones_solicitud(Request $request)
    {
         $conciliaciones = Auth::user()->conciliaciones()->where('tipo_usuario_id','<>',199)
         ->paginate(10);


        //dd($conciliaciones)     ;
        return view('front.conciliaciones.index',compact('conciliaciones'));
    }

    public function conciliacion_store(Request $request)
    {
        
        $periodo = Periodo::where('estado','1')
        ->first();
        $conciliacion = Conciliacion::create([
            'fecha_radicado'=>date('Y-m-d'),
            'num_conciliacion'=>"0000-00",
            'categoria_id'=>219,
            'estado_id'=>174,
            'periodo_id'=> $periodo->id,
            'user_id'=>auth()->user()->id
        ]);

        $conciliacion->usuarios()->attach(auth()->user()->id,[
            'tipo_usuario_id'=>199
        ]);
        $conciliacion->usuarios()->attach(auth()->user()->id,[
            'tipo_usuario_id'=>205
        ]);

        return redirect("/oficina/solicitante/conciliaciones/$conciliacion->id/edit");
    }

    public function conciliacion_edit(Request $request,$id)
    {
        $cursando = TablaReferencia::where(['categoria'=>'cursando','tabla_ref'=>'turnos'])
        ->pluck('ref_nombre','id');
        
        $estudiantes = $this->getEstudiantes();
            $turnos = Turno::join('users','users.idnumber','=','turnos.trnid_estudent')
            ->join('sede_usuarios','sede_usuarios.user_id','=','users.id')
            ->join('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
            ->join('referencias_tablas as rc','rc.id','=','turnos.trnid_color')
            ->join('referencias_tablas as rh','rh.id','=','turnos.trnid_horario')
            ->join('referencias_tablas as cursos','cursos.id','=','users.cursando_id')
            ->join('referencias_tablas as rd','rd.id','=','turnos.trnid_dia')
            ->where('sedes.id_sede',session('sede')->id_sede)
            ->select("turnos.id as id",'users.idnumber','users.cursando_id','users.id as estudiante_id','users.name','users.lastname',
            'trnid_color','rc.ref_value as color_ref_value','rc.ref_nombre as color_nombre','cursos.id as curso_id',
            'cursos.ref_nombre as curso_nombre','rh.ref_nombre as horario_nombre','trnid_horario','rd.ref_nombre as dia_nombre','trnid_dia')
            ->orderBy('trnid_color','asc')->get();
        
            $conciliacion = Conciliacion::find($id);
            $numusers=$conciliacion->usuarios->count();
            $audiencia = AudienciaConciliacion::where('id_conciliacion',$conciliacion->id)->first();
            $salaalterna = SalasAlternasConciliacion::where(['idnumber'=>\Auth::user()->idnumber,"id_conciliacion"=>$conciliacion->id])->first();
            $sala_alterna_url = "";
            if ($salaalterna) {
                $sala_alterna_url=$request->root()."/audiencia"."/salaalaterna"."/".$salaalterna->token_access;
            }
            if (!$audiencia) $audiencia="";  //si no existe lo crea
            $user = $conciliacion->usuarios()->where('user_id',Auth::user()->id)->get();       
                
            if(count($user) > 0){
                return  view('front.conciliaciones.edit',[
                    'cursando'=>$cursando,
                    'turnos'=>$turnos,
                    'estudiantes'=>$estudiantes,
                    'conciliacion'=>$conciliacion,
                    'cont'=>'1',
                    'audiencia'=>$audiencia,
                    'numusers'=>$numusers,
                    'sala_alterna_url'=>$sala_alterna_url]);
            }


               return abort(403);

       
    }

    public function solicitud_show($id)
    {   
        $solicitud = Solicitud::find($id);   
        if($solicitud and $solicitud->user->id == currentUser()->id){
            if($solicitud and $solicitud->type_status_id==171){
                $solicitud->type_status_id = 165;
                $solicitud->save();
                NewPush::channel('solicitudes_coord')
                ->message(['solicitud_id'=>$id,'type_status_id'=>$solicitud->type_status_id,
                'type_status'=>$solicitud->estado->ref_nombre])->publish();
            }
        $tur_aten=  Solicitud::whereIn('type_status_id',[155,156])
        ->whereDate('created_at',date('Y-m-d'))
        ->orderBy("turno", 'desc')->first();
        return view('front.solicitudes.frm_edit_solicitud',compact('solicitud','tur_aten'));
       }
       return view('errors.error'); 
   
    }


    public function getEstudiantes(){

        $users = DB::table('users')
       ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
       ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
       ->leftjoin('turnos' , 'turnos.trnid_estudent','=','users.idnumber')
       ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
       ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
       ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
       ->where ('turnos.trnid_estudent','=',null)
       ->where ('role_id', '6' ) 
       ->where ('users.active', true)
       ->where('sedes.id_sede',session('sede')->id_sede)
       ->select('referencias_tablas.ref_nombre as cursando','users.active','users.id','users.idnumber',
         DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
         ,'role_user.role_id', 'roles.display_name')->orderBy('users.created_at', 'desc')->get();

  return ($users);
 }

}
 