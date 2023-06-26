<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\turnos_docentes;
use App\Periodo;
use DB;

class TurnosDocentesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $docentes = DB::table('users')
        ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
        ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
        ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
        ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
        ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
        ->where ('role_id', '4' )
        ->where ('users.active', true)
        ->where ('users.idnumber','<>', '2020')
        ->where('sedes.id_sede',session('sede')->id_sede)
        ->select('users.active','users.id','ref_nombre','users.idnumber',
          DB::raw('CONCAT(users.name," ",users.lastname) as full_name')
          ,'role_user.role_id', 'roles.display_name')->orderBy('users.created_at', 'desc')->get();
          //dd($docentes);
           return view('myforms.frm_turnos_docentes_list',compact('docentes'))->render();
        //
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
        $periodo = Periodo::join('sede_periodos as sp','sp.periodo_id','=','periodo.id')
		->where('sp.sede_id',session('sede')->id_sede)
        ->where('estado',1)
        ->first();
        $turnos_doc = turnos_docentes::where('trnd_docidnumber',$request->id)->where('trndid_periodo',$periodo->id)->orderBy('trnd_hora_inicio','ASC')->get();
        return response()->json(

            $turnos_doc->toArray()
 
             );
        //dd($turnos_doc);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $response = [];

       $response['docentes'] = $docentes = DB::table('users')
        ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
        ->leftjoin('roles' , 'role_user.role_id','=','roles.id')
        ->leftjoin('sede_usuarios','sede_usuarios.user_id','=','users.id')
        ->leftjoin('sedes','sedes.id_sede','=','sede_usuarios.sede_id')
        ->leftjoin('referencias_tablas' , 'referencias_tablas.id','=','users.cursando_id')
        ->join('turnos_docentes','users.idnumber','=','turnos_docentes.trnd_docidnumber')
        ->where ('role_id', '4' )
        ->where ('users.active', true)
        ->where ('users.idnumber','<>', '2020')
        ->where('sedes.id_sede',session('sede')->id_sede)
        ->select('users.idnumber',DB::raw('CONCAT(users.name," ",users.lastname) as full_name'))
        ->groupBy('users.idnumber')->orderBy('users.created_at', 'desc')->get();


       
        $response['asistencia'] =  $asistencia = DB::table('asistencia_docentes')
        ->where ('reposicion', '0' )
        ->where ('tipo_asis', '149')
        ->select('docidnumber',DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS asistencia'))
        ->groupBy('docidnumber')->orderBy('docidnumber', 'desc')->get();
        //$asistencia = DB::select('SELECT `docidnumber`, SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS asistencia FROM `asistencia_docentes` WHERE `reposicion`=0 AND `tipo_asis` = 149	GROUP BY `docidnumber` ORDER BY `docidnumber` DESC');
        
        $response['permisos'] = $permisos = DB::table('asistencia_docentes')
        ->where ('reposicion', '0' )
        ->where ('tipo_asis', '150')
        ->select('docidnumber',DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS permisos'))
        ->groupBy('docidnumber')->orderBy('docidnumber', 'desc')->get();
        //$permisos = DB::select('SELECT `docidnumber`, SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS permisos FROM `asistencia_docentes` WHERE `reposicion`=0 AND `tipo_asis` = 150	GROUP BY `docidnumber` ORDER BY `docidnumber` DESC');   
        $response['reposicion'] = $reposicion = DB::table('asistencia_docentes')
        ->where ('reposicion', '1' )
        ->where ('tipo_asis', '149')
        ->select('docidnumber',DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS reposicion'))
        ->groupBy('docidnumber')->orderBy('docidnumber', 'desc')->get();
        //$reposicion = DB::select('SELECT `docidnumber`,SUM(TIMESTAMPDIFF(MINUTE, `inicio`, `fin`)) AS reposicion FROM `asistencia_docentes` WHERE `reposicion`=1 AND `tipo_asis` = 149	GROUP BY `docidnumber` ORDER BY `docidnumber` DESC');
        
        return response()->json($response); 

      
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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

    public function updateinfo(Request $request)
    {
        foreach ($request->mydata as $key => $info) { //recibe el json y se dispone a ver las actividades a realizar
            if ($info['accion'] == 'eliminar') {
                $turnos_doc = turnos_docentes::where('trnd_hora_inicio',$info['hora_i'])
                    ->where('trnd_hora_fin',$info['hora_f'])
                    ->where('trnd_dia',$info['value'])
                    ->where('trnd_docidnumber',$info['usuario'])
                    ->delete();
            } elseif ($info['accion'] == 'crear') {
                $periodo = Periodo::where('estado',1)->first();
                $turnos_doc = turnos_docentes::insert([
                    "trnd_docidnumber"=>$info['usuario'],
                    "trnd_dia"=>$info['value'],
                    "trnd_hora_inicio"=>$info['hora_i'],
                    "trnd_hora_fin"=>$info['hora_f'],
                    "trndid_periodo"=>$periodo->id
                    ]);
            } elseif ($info['accion'] == 'actualizar') {
                if (is_array($info['value'])) {
                    $turnos_doc = turnos_docentes::where('trnd_hora_inicio',$info['hora_i'])
                    ->where('trnd_hora_fin',$info['hora_f'])
                    ->where('trnd_docidnumber',$info['usuario'])
                    ->update(["trnd_hora_inicio"=>$info['value'][0],"trnd_hora_fin"=>$info['value'][1]]);        
                }
            }  
        } 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
