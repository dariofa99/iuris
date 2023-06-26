<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requerimiento;
use App\Expediente;
use App\Segmento;
use PDF;
use DB;
use App\ReqAsistencia;
class RequerimientoController extends Controller
{

    public function __construct()
    {
        
        //$this->middleware('permission:edit_usuarios',   ['only' => ['edit']]);
        //$this->middleware('permission:ver_requerimientos',   ['only' => ['index']]);
    }

  public function reqpdfgen($id)
    {

        $requerimiento=  DB::table('requerimientos as r')
           ->leftjoin('expedientes as e', 'r.reqexpid', '=', 'e.expid')
           ->leftjoin('users as u' , 'e.expidnumber','=','u.idnumber')
           ->leftjoin('users as x' , 'e.expidnumberest','=','x.idnumber')
           ->where('r.id', '=',$id)
           ->select('u.idnumber as usr_identifica' ,
            'u.name as usr_nombre', 'u.lastname as usr_ape', 'u.email as usr_email',
            'u.tel1 as usr_tel1','u.tel2 as usr_tel2', 'u.address as usr_direc', 'x.idnumber as est_identifica',
            'x.name as est_nombre', 'x.lastname as est_ape', 'x.email as est_email',
            'x.tel1 as est_tel1','x.tel2 as est_tel2' , 'r.reqdescrip as descrip','r.reqfecha as reqfecha','r.reqhora as reqhora', 'r.reqexpid as expediente','r.reqmotivo as rmotivo', 'e.expestado_id')
            ->get();

          //dd($requerimiento);

        $pdf = PDF::loadView('pdf.invoice', ['requerimiento'=> $requerimiento]);
        return $pdf->stream('invoice.pdf'); 
    }   





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $expediente = Expediente::where('expid',$request->id_control_list_req)->first();

        $requerimientos = Requerimiento::where(['reqexpid'=>$request->id_control_list_req,'reqidest'=>$expediente->expidnumberest])
        ->get();
            $requerimientos->each(function($requerimientos){
                $requerimientos->req_asistencia;
            });
        
        $userSession = \Auth::user()->role()->first();
        
        $data = [
            'requerimientos'=>$requerimientos,
            'userSession'=>$userSession
        ];   

        /*$requerimiento =  DB::table('requerimientos as r')
        ->leftjoin('expedientes as e', 'r.reqexpid', '=', 'e.expid')
        ->where('r.reqexpid', '=',$request->id_control_list_req)
        ->select('r.reqdescrip', 'r.reqfecha','r.reqhora','r.reqid_asistencia','r.reqexpid','r.reqmotivo', 'e.expestado','r.id','r.created_at','r.reqentregado')
        ->get();*/
         
        return response()->json(
                            $data
                        );
        }
//get-----
        $reqasis = ReqAsistencia::all();
        $active_expe='active';
        $requerimientos = Requerimiento::orderby('reqfecha','desc')
        ->join('ref_reqasis','ref_reqasis.reqid_refasis','=','requerimientos.reqid_asistencia')
        ->join('expedientes','expedientes.expid','=','requerimientos.reqexpid')
        ->join('sede_expedientes as se','se.expediente_id','=','expedientes.id')
        ->where('se.sede_id',session('sede')->id_sede)
        ->select('requerimientos.id','requerimientos.created_at','requerimientos.reqmotivo','expedientes.expid',
        'requerimientos.reqfecha','requerimientos.reqhora','ref_reqasis.ref_reqasistencia','reqentregado','evaluado')
        ->paginate(20);
            
        if (currentUser()->hasRole('coordprac')) {
            $requerimientos = Requerimiento::orderby('reqfecha','desc')
            ->join('expedientes','expedientes.expid','=','requerimientos.reqexpid')
            ->join('ref_reqasis','ref_reqasis.reqid_refasis','=','requerimientos.reqid_asistencia')
            ->join('sede_expedientes as se','se.expediente_id','=','expedientes.id')
            ->where('se.sede_id',session('sede')->id_sede)
            ->select('requerimientos.id','requerimientos.created_at','requerimientos.reqmotivo','expedientes.expid',
            'requerimientos.reqfecha','requerimientos.reqhora','ref_reqasis.ref_reqasistencia','reqentregado','evaluado')
            ->paginate(20);
            
        } 
        if (currentUser()->hasRole('secretaria')) {
            $requerimientos = Requerimiento::orderby('created_at','desc')
            ->join('ref_reqasis','ref_reqasis.reqid_refasis','=','requerimientos.reqid_asistencia')
            ->join('expedientes','expedientes.expid','=','requerimientos.reqexpid')
            ->join('sede_expedientes as se','se.expediente_id','=','expedientes.id')
            ->where('se.sede_id',session('sede')->id_sede)
            ->select('requerimientos.id','requerimientos.created_at','requerimientos.reqmotivo','expedientes.expid',
            'requerimientos.reqfecha','requerimientos.reqhora','ref_reqasis.ref_reqasistencia','reqentregado','evaluado')
            ->paginate(20);

        }  
       if ($request->tipo_busqueda!=null) {
                $requerimientos = Requerimiento::orderBy('reqfecha','asc')
                ->join('ref_reqasis','ref_reqasis.reqid_refasis','=','requerimientos.reqid_asistencia')
                ->join('expedientes','expedientes.expid','=','requerimientos.reqexpid')
                ->join('sede_expedientes as se','se.expediente_id','=','expedientes.id')
                ->where('se.sede_id',session('sede')->id_sede)
                ->Criterio($request->data,$request->tipo_busqueda)
                ->select('requerimientos.id','requerimientos.created_at','requerimientos.reqmotivo','expedientes.expid',
                        'requerimientos.reqfecha','requerimientos.reqhora','ref_reqasis.ref_reqasistencia','reqentregado','evaluado')
                ->paginate(10);
                $request = $request->all();
        }

       // 
   
return view('myforms.frm_requerimiento_list',compact('requerimientos','reqasis','request','active_expe'));
       
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
        

        if($request->ajax()){
        $expediente_id= $request->id_control_list_req;
              if(isset($request->reqexpid)){
                if(isset($request->fechareq) and isset($request->horareq)){//si existe fecha y hora de cita
                $reqfechahoracomp12=$request->fechareq." ".$request->horareq;
                 }else{
                    $reqfechahoracomp12='';
                 }                
                Requerimiento::create([
                            //'reqid'=> '.',                             
                            'reqexpid'=> $request['reqexpid'],
                            'reqidsolicitan'=> $request['reqidsolicitan'],
                            'reqidest'=> $request['reqidest'],                            
                            'reqmotivo'=> $request['reqmotivo'],
                            'reqdescrip'=> $request['reqdescrip'],
                            'reqfecha'=> $request['reqfecha'],
                            'reqhora'=>$request['reqhora'],
                            'reqid_asistencia'=>5,
                            'reqfechahoracomp12'=> $reqfechahoracomp12,                            
                            'requsercreated'=> currentUser()->idnumber,
                            'requserupdated'=> currentUser()->idnumber,
                    ]);
                $requerimiento= Requerimiento::where('reqexpid','=' ,$request->reqexpid)->orderby('created_at','DESC')->take(1)->get();                
                return response()->json(
                    $request->all()
                ); 
            }else{
                if(isset($request->bandera)){
                    if($request->bandera==0){
                        //$requerimiento= Requerimiento::where('reqexpid','=' ,$expediente_id)->orderby('created_at','DESC')->get();
                              //$actuaciones= Actuacion::where('actexpid','=' ,$expediente_id)->get();
            $requerimiento =  DB::table('requerimientos as r')
           ->leftjoin('expedientes as e', 'r.reqexpid', '=', 'e.expid')
           ->where('r.reqexpid', '=',$expediente_id)
           ->select('r.reqdescrip', 'r.reqfecha', 'r.reqexpid','r.reqmotivo', 'e.expestado','r.id')
           ->get();
            return response()->json(
                            $requerimiento->toArray()
                        );
                    }
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $requerimiento = Requerimiento::find($id);
        $requerimiento->req_asistencia;        
        $solicitante = $requerimiento->expediente->solicitante; 
        $requerimiento->notas_f = $requerimiento->get_notas(); 
        
        $data = [
            'requerimiento'=>$requerimiento,            
        ];
        return response()->json($data);
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
        //  return response()->json($request->all());  
        
        $requerimiento = Requerimiento::find($id);
        $requerimiento->fill($request->all());      
        if (isset($request->ntaetica) and isset($request->ntaconcepto)) {
             $data = [
                  //'ntaaplicacion'=>$request->ntaaplicacion,
                  //'ntaconocimiento'=>$request->ntaconocimiento,
                  'ntaetica'=>$request->ntaetica,
                  'ntaconcepto'=>$request->ntaconcepto,
                  'orgntsid'=>$request->orgntsid,
                  'segid'=>$request->segid,
                  'perid'=>$request->perid,
                  'tpntid'=>$request->tpntid,
                  'expidnumber'=>$requerimiento->reqexpid,
                  'estidnumber'=>$requerimiento->reqidest,
                  'docidnumber'=>\Auth::user()->idnumber, 
                  'tbl_org_id'=>$requerimiento->id, 
                ]; 
        $requerimiento->asignarNotas($data);
        $requerimiento->evaluado=1;
        $requerimiento->notas = json_encode($data);

        }
        $requerimiento->save();
        return response()->json($requerimiento);  
    }

    public function updateReq(Request $request, $id)
    {
        $requerimiento = Requerimiento::find($id);
        if ($requerimiento->reqentregado) {
            $requerimiento->reqentregado = 0;
        }else{
            $requerimiento->reqentregado = 1;
        }        
        $requerimiento->save();
        return response()->json($request->all());
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requerimiento = Requerimiento::find($id);
        $requerimiento->delete();
        return response()->json($requerimiento);
    }
}
