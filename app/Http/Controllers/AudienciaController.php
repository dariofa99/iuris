<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Redirect;
use DB;
use App\Turno;
use App\TablaReferencia;
use App\Conciliacion;
use App\AudienciaConciliacion;
use App\Notifications\SolicitudEstudiantesConciliacion;
use App\PdfReporte;
use App\PdfReporteDestino;
use App\SalasAlternasConciliacion;
use App\Sede;
use Facades\App\Facades\NewPush;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Traits\PdfReport as TraitPdf;
class AudienciaController extends Controller
{
    use TraitPdf;
    public function getSalasAudiencia(Request $request,$id,$cont){
        $conciliacion = Conciliacion::find($id);
        $numusers=$conciliacion->usuarios->count();
        $view = view('myforms.conciliaciones.componentes.audiencia_salas_alternas_active_ajax',compact('conciliacion','cont','numusers'))->render();
        return response()->json([
            'view'=>$view
        ]);
    }
    public function postSalasAudienciaCreate(Request $request){
        $statereturn=0;
        $salasalternasdel = SalasAlternasConciliacion::where('id_conciliacion',$request->id['id_conciliacion']);
        $salasalternasdel->delete();
        if (isset($request->idnumbers)) {
            $statereturn=1;
            $socketroom= "RoomAlterConciliacionactive";
            foreach ($request->idnumbers as $key => $value) {
                $access_token =  hash('sha512', $request->id['id_conciliacion'].$request->rooms[$key].$value);
                $salasalternas = new SalasAlternasConciliacion();
                $salasalternas->id_conciliacion= $request->id['id_conciliacion'];
                $salasalternas->idnumber = $value;
                $salasalternas->fecha = Carbon::now()->format('Y-m-d');
                $salasalternas->access = $request->rooms[$key];
                $salasalternas->token_access = $access_token;
                $salasalternas->save();

                NewPush::channel($socketroom.$value)
                ->message(["room"=>$request->rooms[$key],"id_conciliacion"=>$request->id['id_conciliacion'],"url"=>$request->root()."/audiencia"."/salaalaterna"."/".$access_token])
                ->publish();

            }
        }
        return response()->json($statereturn);


    }
    public function getUsersSalasAudiencia($id){
        $salasalternas = SalasAlternasConciliacion::where('id_conciliacion',$id)->get();
        $salas =SalasAlternasConciliacion::where('id_conciliacion',$id)->select('access')->groupby('access')->get();
        return response()->json(['salasalternas'=>$salasalternas,'salas'=>$salas]);
    }

    public function ExternoSalaAudiencia(Request $request,$code){

        $audiencia = AudienciaConciliacion::where('access_code',$code)->first();
        if ($audiencia) {
            $fecha_actual= Carbon::parse(Carbon::now()->format('Y-m-d'));
            $fecha_audiencia = Carbon::parse($audiencia->fecha);
            $diferencia = $fecha_audiencia->diffInDays($fecha_actual);
            if ($diferencia==0){
                //hoy es la audiencia
                if (count($request->all()) > 0) { //hay datos post
                    $id_conciliacion = $audiencia->id_conciliacion;
                    $conciliacion = Conciliacion::find($id_conciliacion);
                    $usuario = $conciliacion->usuarios->where('idnumber',$request->idnumber)->first();
                    if ($usuario) {
                        Auth::login($usuario);
                        $sede = Sede::find($usuario->sedes[0]->id_sede);             
                        if($sede)session(["sede"=>$sede]);                        
                        if ($usuario->idnumber == $request->idnumber) {
                            $salaalterna = SalasAlternasConciliacion::where(['idnumber'=>$request->idnumber,"id_conciliacion"=>$id_conciliacion])->first();
                            $sala_alterna_url = "";
                            if ($salaalterna) {
                                $sala_alterna_url=$request->root()."/audiencia"."/salaalaterna"."/".$salaalterna->token_access;
                            } 
                          //  dd("ss");
                            //acceso a la videollamada
                            Session::flash('message-info', 'Recuerda, la audiencia de conciliación está agendada para hoy a las '.$audiencia->hora.' si no hay nadie, espera o ingresa a la hora indicada.');
                            return view('myforms.conciliaciones_audiencias.audiencia_invitado',compact('conciliacion','usuario','id_conciliacion','sala_alterna_url'));
                        }
                    }
                    //usuario no autorizado
                    Session::flash('message-danger', 'Error de acceso, la audiencia de conciliación solicitada no esta disponible en este momento, verifica el link de acceso o vuelve a ingresar tu número de cédula.');
                    return view('myforms.conciliaciones_audiencias.frm_audiencia_invitado_input',compact('code'));
                } else {
                    //pide num cedula
                    return view('myforms.conciliaciones_audiencias.frm_audiencia_invitado_input',compact('code'));
                }
            }
            if ($fecha_actual>$fecha_audiencia) {
                 //la conciliacion ya paso
                Session::flash('message-danger', 'Error de acceso, La audiencia de conciliación solicitada no esta disponible o ya pasó, verifica el link de acceso.');
                return view('myforms.conciliaciones_audiencias.frm_audiencia_invitado_input',compact('code'));
            }
            //la conciliacion es en otra fecha futura
            Session::flash('message-danger', 'La audiencia de conciliación solicitada no esta disponible en este momento, debes ingresar en la fecha señalada. Confirma la fecha o verifica el link de acceso.');
           return view('myforms.conciliaciones_audiencias.frm_audiencia_invitado_input',compact('code'));
        } else {
            //la audiencia solicitada no existe
            Session::flash('message-danger', 'Error de acceso, la audiencia de conciliación solicitada no esta disponible, verifica el link de acceso.');
            return view('myforms.conciliaciones_audiencias.frm_audiencia_invitado_input',compact('code'));
        }
    }

    public function audienciaCreate(Request $request){
        //dd($request->id);
        $state="actualizado";
        $audiencia = AudienciaConciliacion::where('id_conciliacion',$request->id)->first();
        if (!$audiencia) { //si no existe lo crea
            $state="registrado";
            $audiencia = new AudienciaConciliacion();
            $audiencia->id_conciliacion= $request->id;
            $audiencia->access_code = hash('crc32', hash('sha256', $request->id));
        }
        //si existe acutaliza
            $audiencia->fecha = $request->fecha;
            $audiencia->hora = $request->hora;
            $audiencia->save();

        return $state;
    }

    public function getSalaAlternaAudciencia(Request $request, $code){
        //resources\views\myforms\conciliaciones_audiencias\sala_alterna_audiencia.blade.php
        //audiencia/salaalaterna/{code}
        $salasalternas = SalasAlternasConciliacion::where('token_access',$code)->first();
        if ($salasalternas) {
            $fecha_actual= Carbon::parse(Carbon::now()->format('Y-m-d'));
            //dd($fecha_actual);
            $fecha_sala = Carbon::parse($salasalternas->fecha);
            $diferencia = $fecha_sala->diffInDays($fecha_actual);
            if ($diferencia==0){
                            $access_room =  hash('sha512', $salasalternas->id_conciliacion.$salasalternas->access);
                            $usuario  = "";
                            $sala  = $salasalternas->access;
                            //acceso a la videollamada
                            Session::flash('message-info', 'Si quieres regresar a la audiencia de conciliación cierra esta página, regresa a la página anterior y dale clic en el botón “regresar a audiencia”.');
                            return view('myforms.conciliaciones_audiencias.sala_alterna_audiencia',compact('access_room','usuario','sala'));

            }

        }
        //error de acceso se redirige a una pagina no existente
        return redirect('/audiencia/salasalternas/'. $code.'/');
    }

    public function getconciliacionRolList(Request $request){
        $rollist = DB::table('referencias_tablas')
        ->select('id','ref_nombre')
        ->where('categoria','type_user_conciliacion')
        ->get();

        return compact('rollist');

    }

    public function getEstudianteRol($idconciliacion){


        $usersrol = DB::table('conciliacion_has_user')
        ->join('users','conciliacion_has_user.user_id','=','users.id')
        ->join('role_user','role_user.user_id','=','users.id')
        ->join('referencias_tablas','conciliacion_has_user.tipo_usuario_id','=','referencias_tablas.id')
        ->select('users.idnumber','ref_nombre','tipo_usuario_id')
        ->where('role_id','6')
        ->where('conciliacion_id',$idconciliacion)
        ->get();

        $cont_est = DB::table('conciliacion_has_user')
        ->join('users','conciliacion_has_user.user_id','=','users.id')
        ->join('role_user','role_user.user_id','=','users.id')
        ->select('users.idnumber',DB::raw('count(users.idnumber) AS numconsi'))
        ->where('role_id','6')
        ->whereRaw('tipo_usuario_id = 198 or tipo_usuario_id = 199')
        ->groupBy('users.idnumber')
        ->get();
        return response()->json(['usersrol'=>$usersrol,'cont_est'=>$cont_est]);

    }

    public function postConciliacionEstRolUpate(Request $request){
        //$request->idrol

        $user = User::where('idnumber',$request->id)->first();
        $state = '0';
        $action = '';
        $conciliacion = Conciliacion::find($request->idconciliacion);
        $conciliacion_has_user = DB::table('conciliacion_has_user')->where([
            'conciliacion_id' => $request->idconciliacion,
            'user_id' => $user->id,
           
        ])->first();

        if ($conciliacion_has_user) {
            if ($request->idrol == '000') {
                $delete_conciliacion_has_user = DB::table('conciliacion_has_user')
                ->where('id', $conciliacion_has_user->id)->delete();
                $state = $delete_conciliacion_has_user;
                $action = 'delete';
            } else {
                $update_conciliacion_has_user = DB::table('conciliacion_has_user')
                ->where('id', $conciliacion_has_user->id)
                ->update(['tipo_usuario_id' => $request->idrol]);
                $state = $update_conciliacion_has_user;
                $action = 'update';
            }
        } else {
            if ($request->idrol != '000') {
                $conciliacion->usuarios()->attach($user->id,[
                    'tipo_usuario_id' => $request->idrol,
                    'conciliacion_id' => $request->idconciliacion,
                    'user_id' => $user->id,
                    'estado_id'=>229
                ]);
              /*   $insert_conciliacion_has_user = DB::table('conciliacion_has_user')->insert([
                    'tipo_usuario_id' => $request->idrol,
                    'conciliacion_id' => $request->idconciliacion,
                    'user_id' => $user->id,
                    'estado_id'=>229
                ]); */
                $state = 1;
                $action = 'insert';
            }
        }
        //$users = User::whereIn('email',$request->correo_send)->get();
        $reportes = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
            $query->where('is_copy', 0);
        })
            /* ->whereHas('temporales', function (Builder $query) use ($request) {
                $query->where('conciliacion_id', $request->conciliacion_id);
            }) */
            //    ->with('users')
            ->where('tabla_destino','=','conciliaciones_email')
            ->where('categoria',$request->categoria)
            ->get();
         //   return response()->json($request->all());
        
        if(count($reportes) > 0 AND $request->idrol=='203') $reporte = PdfReporte::find($reportes[0]->reporte_id);
        if(count($reportes) > 0 AND $request->idrol=='204') $reporte = PdfReporte::find($reportes[0]->reporte_id);
        if(isset($reporte)) {
            $bodytag =  $this->getBody($reporte,$conciliacion);    
            Notification::send($user, new SolicitudEstudiantesConciliacion( $bodytag ,$conciliacion ));
        }
        return compact('state','action','user');

    }

    public function getConciliacionTurnosEst($data,$id){ 

        $conciliacion = Conciliacion::find($id);
        $estudiantes = $this->getEstudiantes();
        if ($data == "all") {
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
        } else {
            $turnos = Turno::join('users','users.idnumber','=','turnos.trnid_estudent')
            ->join('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->join('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->join('referencias_tablas as rc','rc.id','=','turnos.trnid_color')
           ->join('referencias_tablas as rh','rh.id','=','turnos.trnid_horario')
           ->join('referencias_tablas as cursos','cursos.id','=','users.cursando_id')
           ->join('referencias_tablas as rd','rd.id','=','turnos.trnid_dia')
           ->where('cursando_id',$data)
           ->where('sedes.id_sede',session('sede')->id_sede)
           ->select("turnos.id as id",'users.idnumber','users.cursando_id','users.id as estudiante_id','users.name','users.lastname',
           'trnid_color','rc.ref_value as color_ref_value','rc.ref_nombre as color_nombre','cursos.id as curso_id',
           'cursos.ref_nombre as curso_nombre','rh.ref_nombre as horario_nombre','trnid_horario','rd.ref_nombre as dia_nombre','trnid_dia')
           ->orderBy('trnid_color','asc')->get();
        }

        return view('myforms.conciliaciones.componentes.list_turno_estudiante',compact('estudiantes','turnos','conciliacion'))->render();
       
    

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

    public function calendarAudiencias() {
        //$audiencias = AudienciaConciliacion::All();
        $conciliaciones = Conciliacion::join('conciliacion_audiencias','conciliacion_audiencias.id_conciliacion','conciliaciones.id')->get();
        return view('myforms.conciliaciones.agenda_audiencias',compact('conciliaciones'));

    }

    public function getChangeChatRoom(Request $request,$chatroom) { 
       // return $request->all();
       if($request->conciliacion_id==$request->useridnumber){
           $chatroom = "conciliacion-$request->conciliacion_id";
        }else{
        $user_idnumber = Auth::user()->idnumber;
        $chatroom = "conciliacion-$request->conciliacion_id$user_idnumber$request->useridnumber";
        if($request->useridnumber < $user_idnumber){
            $chatroom = "conciliacion-$request->conciliacion_id$request->useridnumber$user_idnumber";
        }
       }
       
        return view('myforms.conciliaciones.componentes.chat_room_ajax',compact('chatroom'))->render();

    }

}