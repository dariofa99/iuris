<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Autorizacion;
use App\AsignacionCaso;
use PDF;
class AutorizacionesController extends Controller
{
    public function __construct()
    {        
          $this->middleware('permission:ver_autorizaciones',   ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $autorizaciones = Autorizacion::join('sede_autorizaciones as sa','sa.autorizacion_id','=','autorizaciones.id')
        ->where('sa.sede_id',session('sede')->id_sede)
        ->search($request)
        ->orderBy('autorizaciones.created_at','desc')->paginate(100);
       // dd($autorizaciones);
        if($request->ajax()){
            return view('myforms.frm_autorizaciones_list_ajax',compact('autorizaciones'))->render();
        }   
        return view('myforms.frm_autorizaciones_list',compact('autorizaciones'));
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
       // return response()->json($request->all());
        $autorizacion = new Autorizacion($request->all());
        $autorizacion->user_solicitante_id = \Auth::user()->id;
        $autorizacion->user_aprobo_id = \Auth::user()->id;
        $autorizacion->asig_caso_id = \Auth::user()->asig_caso()->where(['asigexp_id'=>$request->exp_id,'activo'=>1])->first()->id;
        $autorizacion->save();      
        $asignacion = AsignacionCaso::find($autorizacion->asig_caso_id);
        if(session()->has('sede')){
            $autorizacion->sedes()->attach(session('sede')->id_sede);
          }     
        $view = view('myforms.components_exp.frm_autorizaciones_ajax',compact('asignacion'))->render();
        
        return response()->json(['view'=>$view]);
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
        $autorizacion =  Autorizacion::find($id);
         return response()->json($autorizacion);
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
        //return $request->all();
        $autorizacion =  Autorizacion::find($id);
        $autorizacion->fill($request->all());
        $autorizacion->save();

        if($request->has('estado')){
            if($request->estado==1){
                $autorizacion->fecha_autorizado = date('Y-m-d');
                $autorizacion->user_aprobo_id = currentUser()->id;
            }else{
                $autorizacion->fecha_autorizado = null;
            }
            $autorizacion->save();
        }

        $asignacion = AsignacionCaso::find($autorizacion->asig_caso_id);




        if($request->has('estado') and $request->vista == 'autorizaciones'){
            $autorizaciones = Autorizacion::join('sede_autorizaciones as sa','sa.autorizacion_id','=','autorizaciones.id')
            ->where('sa.sede_id',session('sede')->id_sede)
            ->orderBy('autorizaciones.created_at','desc')->paginate(100);
            $view = view('myforms.frm_autorizaciones_list_ajax',compact('autorizaciones'))->render();
        }else{
            $view = view('myforms.components_exp.frm_autorizaciones_ajax',compact('asignacion'))->render();
       
        }
         
        return response()->json(['view'=>$view]);
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $autorizacion =  Autorizacion::find($id);
        $autorizacion->delete();    
        $asignacion = AsignacionCaso::find($autorizacion->asig_caso_id);
        $view = view('myforms.components_exp.frm_autorizaciones_ajax',compact('asignacion'))->render();
        
        return response()->json(['view'=>$view]);
    }

    public function descargarPdf($id)
    {

        $autorizacion =  Autorizacion::find($id);
       // dd($autorizacion);    
        if($autorizacion and $autorizacion->estado){
            $pdf = PDF::loadView('pdf.autorizacion', ['autorizacion'=> $autorizacion]);
            return $pdf->stream('autorizacion.pdf'); 
        }else{
            $url = '/expedientes/'; 
            return view('errors.error',compact('url'));
        } 
        
    } 

    public function verificar()
    {

            return view('myforms.frm_verificar_autorizacion');
        
        
    } 

    public function verificarPdf(Request $request)
    {

        $autorizacion =  Autorizacion::where('num_radicado',$request->num_radicado)->first();
        //dd($autorizacion);
        if($autorizacion and $autorizacion->estado){
            $pdf = PDF::loadView('pdf.autorizacion', ['autorizacion'=> $autorizacion]);
            return $pdf->stream('autorizacion.pdf'); 
        }else{
            return \Redirect::back()->withDanger( 'El número de radicado no existe o esta sin autorizar');
           //return "La autorización no existe";
        }
        
    } 
}
