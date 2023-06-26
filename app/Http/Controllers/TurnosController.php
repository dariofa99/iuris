<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Turno;
use DB;
use App\Periodo;
use App\TablaReferencia;
use App\User;
use App\Oficina;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EstudiantesCursosExport;
class TurnosController extends Controller
{


    public function index(Request $request){



    		$cursando = TablaReferencia::where(['categoria'=>'cursando','tabla_ref'=>'turnos'])->pluck('ref_nombre','id');
    		$ref_color = TablaReferencia::where(['categoria'=>'color','tabla_ref'=>'turnos'])->pluck('ref_nombre','id');
    		$ref_horarios = TablaReferencia::where(['categoria'=>'horario','tabla_ref'=>'turnos'])->pluck('ref_nombre','id');
            $oficinas = Oficina::pluck('nombre','id');
            $dias = TablaReferencia::where(['categoria'=>'dias_turno','tabla_ref'=>'turnos'])->pluck('ref_nombre','id');
            
            $estudiantes = $this->getEstudiantes();


    		$turnos = Turno::join('users','users.idnumber','=','turnos.trnid_estudent')
            ->join('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->join('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->join('referencias_tablas as rc','rc.id','=','turnos.trnid_color')
           ->join('referencias_tablas as rh','rh.id','=','turnos.trnid_horario')
           ->join('referencias_tablas as cursos','cursos.id','=','users.cursando_id')
           ->join('oficinas','oficinas.id','=','turnos.trnid_oficina')
           ->join('referencias_tablas as rd','rd.id','=','turnos.trnid_dia')
           ->where(function($query) use ($request){
            if ($request->has('data_search')) {
                return $query->where('cursos.id',$request->data_search);
            }
           })        
           ->where('sedes.id_sede',session('sede')->id_sede)
           ->select("turnos.id as id",'users.idnumber','users.cursando_id','users.id as estudiante_id','users.name','users.lastname',
           'trnid_color','rc.ref_value as color_ref_value','rc.ref_nombre as color_nombre','cursos.id as curso_id',
           'cursos.ref_nombre as curso_nombre','rh.ref_nombre as horario_nombre','trnid_horario','trnid_oficina',
           'oficinas.nombre as oficina_nombre','rd.ref_nombre as dia_nombre','trnid_dia')
           ->orderBy('trnid_color','asc')->get(); 
          //  dd($turnos);
    		$data_search='';
 
    		if (isset($request->data_search) and !$request->ajax()) {
    		$data_search = $request->data_search;
    		$colores = DB::table('turnos')
    		->join('users','users.idnumber','=','turnos.trnid_estudent')
            ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
            ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
    		->select(
    			DB::raw('SUM(if(trnid_color = "105", 1, 0)) AS amarillo'),
    			DB::raw('SUM(if(trnid_color = "106", 1, 0)) AS azul'),
    			DB::raw('SUM(if(trnid_color = "107", 1, 0)) AS verde'),
    			DB::raw('SUM(if(trnid_color = "108", 1, 0)) AS gris'),
    			DB::raw('SUM(if(trnid_color = "109", 1, 0)) AS rojo'),
                DB::raw('SUM(if(trnid_color = "120", 1, 0)) AS morado')
    		)
            ->where('sedes.id_sede',session('sede')->id_sede)
            ->where('users.cursando_id',$request->data_search)->get();
            
            $colores = (object)$colores;

    		}elseif(!$request->ajax()){
                $colores = DB::table('turnos')
            ->join('users','users.idnumber','=','turnos.trnid_estudent')
            ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
            ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
            ->select(
                DB::raw('SUM(if(trnid_color = "105", 1, 0)) AS amarillo'),
                DB::raw('SUM(if(trnid_color = "106", 1, 0)) AS azul'),
                DB::raw('SUM(if(trnid_color = "107", 1, 0)) AS verde'),
                DB::raw('SUM(if(trnid_color = "108", 1, 0)) AS gris'),
                DB::raw('SUM(if(trnid_color = "109", 1, 0)) AS rojo'),
                DB::raw('SUM(if(trnid_color = "120", 1, 0)) AS morado')
            )
            ->where('sedes.id_sede',session('sede')->id_sede)
            ->get();
            }

        if ($request->ajax()) {
            if ($request->data_search!='all' and !empty($request->data_search)) {
            $estudiantes = $this->getEstudiante($request);
            }

            return view('myforms.frm_turnos_students_list_ajax',compact('estudiantes'))->render();
        }

    		/*SELECT SUM(if(trnid_color = '105', 1, 0)) AS amarillo, SUM(if(trnid_color = '106', 1, 0)) AS Azul, SUM(if(trnid_color = '107', 1, 0)) AS verde, SUM(if(trnid_color = '108', 1, 0)) AS gris, SUM(if(trnid_color = '108', 1, 0)) AS rojo FROM users, turnos WHERE trnid_estudent`=idnumber` AND `cursando_id`='116'*/

//dd($colores[0]->amarillo);
//dd($estudiantes);
    		return  view('myforms.frm_turnos_students_list',[
                'dias'=>$dias,'oficinas'=>$oficinas,'ref_horarios'=>$ref_horarios,
                'ref_color'=>$ref_color,
                'turnos'=>$turnos,'cursando'=>$cursando,
                'colores'=>$colores,'data_search'=>$data_search,
                'estudiantes'=>$estudiantes]);
	}

	public function store(Request $request){
        $periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
        ->where('estado',true)

        ->first();
        $user = User::where('idnumber',$request->idnumber)->first();
        $user->cursando_id = $request->cursando_id;
        $user->save();
        $turno = new Turno();
        $turno->trnid_color = $request->color_id;
        $turno->trnid_horario=$request->horario_id;
        $turno->trnid_estudent = $request->idnumber;
        $turno->trnid_periodo=$periodo->id;
        $turno->trnid_oficina=$request->trnid_oficina;
        $turno->trnid_dia=$request->trnid_dia;     
        $turno->trnusercreated = currentUser()->idnumber;
        $turno->trnuserupdated = currentUser()->idnumber;
        $turno->save(); 

        return response()->json($turno);
	}


	public function edit($id){

	}

	public function update(Request $request,$id){

		$user = User::find($request->estudiante_id);
		$user->cursando_id = $request->cursando_id;
		$user->save();
		$turno = Turno::find($id);
		$turno->trnid_color=$request->color_id;
        $turno->trnid_horario =$request->horario_id;
        $turno->trnid_oficina =$request->trnid_oficina;
        $turno->trnid_dia =$request->trnid_dia;
		$turno->save();


		return response()->json($request->all());

	}

	public function destroy($id){

    $turno = Turno::find($id);
    $turno->delete();
    return response()->json($id);

	}



	/*public function changeState($id){
		$users= DB::table('periodo')->update(['estado'=>false]);
		$segmento = Periodo::find($id);
		$segmento->estado = true;
		$segmento->save();
		return redirect('/periodos/');
	}*/

	public function deleteAllTurnos(){
		$turnos = DB::table('turnos')
        ->join('users','users.idnumber','=','turnos.trnid_estudent')
        ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
        ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
        ->where('sedes.id_sede',session('sede')->id_sede)
        ->delete();
		return response()->json($turnos);

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
             ,'role_user.role_id', 'roles.display_name')
             ->orderBy('users.created_at', 'desc')->get();

      return ($users);
     }

     public function getEstudiante($request){

            $users = DB::table('users')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
           ->leftjoin('turnos' , 'turnos.trnid_estudent','=','users.idnumber')
           ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
           ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->where ('turnos.trnid_estudent','=',null)
           ->where ('users.idnumber', $request->data_search)
           ->where ('role_id', '6' )
           ->where ('users.active', true)
           ->where('sedes.id_sede',session('sede')->id_sede)
           ->select('referencias_tablas.ref_nombre as cursando','users.active','users.id','users.idnumber',
             DB::raw('CONCAT(users.name," ",users.lastname) as full_name'))->get();

      return ($users);
     }

  public function reporasistencia()
     {
        $periodo= DB::table('periodo')
        ->join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
        ->where('estado', '=', '1')
        ->first();
        $fecha=$periodo->prdfecha_inicio;

      $rasistencia= DB::table('users')
             ->leftjoin('asistencia',  'users.idnumber','=','asistencia.astid_estudent')
             ->join('referencias_tablas as ref','ref.id','=','users.cursando_id')
             ->join('role_user', 'users.id', '=', 'role_user.user_id')
             ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
            ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
             ->select('users.name', 'users.lastname','ref.ref_nombre', DB::raw('SUM(IF(asistencia.astid_tip_asist = 121 OR asistencia.astid_tip_asist = 127 OR asistencia.astid_tip_asist = 128, 1, 0)) AS asistencia'), DB::raw('SUM(IF(asistencia.astid_tip_asist = 122, 1, 0)) AS falta_simple'), DB::raw('SUM(IF(asistencia.astid_tip_asist = 123 OR asistencia.astid_tip_asist = 126, 1, 0)) AS falta_doble'), DB::raw('SUM(IF(asistencia.astid_tip_asist = 125, 1, 0)) AS reposicion'),'users.idnumber' )
             ->where ('users.active', true)
             ->where ('role_id', '6' )
             ->where ('astfecha', '>=', $fecha)
             ->where('sedes.id_sede',session('sede')->id_sede)
             ->groupBy('users.idnumber')
             ->get();
                //dd($rasistencia);
                        return response()->json(

           $rasistencia->toArray()

            );
     }

    public function reporAsistenciaDetalles($idnum)
     {
        $periodo= DB::table('periodo')
        ->join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
        ->where('estado', '=', '1')
        ->first();
        $fecha=$periodo->prdfecha_inicio;

      $rasistenciadet= DB::table('asistencia')
                ->join('users',  'users.idnumber','=','asistencia.astid_estudent')
                ->join('referencias_tablas as ref','ref.id','=','astid_tip_asist')
                ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
                ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
                ->select('asistencia.astid_estudent','asistencia.astid_lugar', 'astdescrip_asist', 'ref.ref_nombre', 'astfecha', 'users.name', 'users.lastname')
                ->where('asistencia.astid_estudent', '=' , $idnum)
                ->where ('astfecha', '>=', $fecha)
                ->where('sedes.id_sede',session('sede')->id_sede)
                ->orderBy(DB::raw("FIELD(astid_tip_asist,'122','123','126','125','124','127','128','121')"))
                ->orderBy('astfecha','DESC')
                ->get();
               // dd($idnum);
                        return response()->json(

           $rasistenciadet->toArray()

            );
     }

     public function descargarTurnosExcel(Request $request){
        $users = Turno::join('users','users.idnumber','=','turnos.trnid_estudent')
            ->join('sede_usuarios','sede_usuarios.user_id','=','users.id')
           ->join('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
           ->join('referencias_tablas as rc','rc.id','=','turnos.trnid_color')
           ->join('referencias_tablas as rh','rh.id','=','turnos.trnid_horario')
           ->join('referencias_tablas as cursos','cursos.id','=','users.cursando_id')
           ->join('oficinas','oficinas.id','=','turnos.trnid_oficina')
           ->join('referencias_tablas as rd','rd.id','=','turnos.trnid_dia')
           ->where('sedes.id_sede',session('sede')->id_sede)
           ->where(function($query) use ($request){
            if ($request->has('data_search') and $request->get('data_search')!='') {
                return $query->where('cursos.id',$request->data_search);
            }
           })  
           ->select("turnos.id as id",'users.idnumber','users.cursando_id','users.id as estudiante_id','users.name','users.lastname',
           'trnid_color','rc.ref_value as color_ref_value','rc.ref_nombre as color_nombre','cursos.id as curso_id',
           'cursos.ref_nombre as curso_nombre','rh.ref_nombre as horario_nombre','trnid_horario','trnid_oficina',
           'oficinas.nombre as oficina_nombre','rd.ref_nombre as dia_nombre','trnid_dia')
           ->orderBy('trnid_color','asc')->get(); 

           ob_end_clean(); // this
           ob_start();
           return Excel::download(new EstudiantesCursosExport($users), 'estudiantes_'.$request->data_search.'.xlsx');

          // return view("report.estudiantes_cursos",compact("users"));
         
     }

}
