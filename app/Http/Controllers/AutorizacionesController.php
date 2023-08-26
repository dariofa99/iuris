<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Autorizacion;
use App\AsignacionCaso;
use App\Services\AutorizacionesService;
use App\Services\ExpedientesService;
use Illuminate\Support\Facades\Auth;
use PDF;
class AutorizacionesController extends Controller
{
    private $autorizacionesService;
    private $segmentosService;
    private $expedienteService;

    public function __construct(
        ExpedientesService $expedienteService,
        AutorizacionesService $autorizacionesService

      
    ) {
        $this->middleware('permission:ver_autorizaciones',   ['only' => ['index']]);
        $this->expedienteService = $expedienteService;
        $this->autorizacionesService = $autorizacionesService;
    }
  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $autorizaciones = $this->autorizacionesService->index($request);
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
        $expediente = $this->expedienteService->findWithFilter([
            'expid' => $request->exp_id           
        ]);
        $asignacion = $expediente->asignacion;       
        $autorizacion = new Autorizacion($request->all());
        $autorizacion->user_solicitante_id = Auth::user()->id;
        $autorizacion->user_aprobo_id = Auth::user()->id;
        $autorizacion->asig_caso_id = $asignacion->id;
        $autorizacion->save();          
        if(session()->has('sede')){
            $autorizacion->sedes()->attach(session('sede')->id_sede);
          }     
        $view = view('myforms.components_exp.frm_autorizaciones_ajax',compact('expediente'))->render();
        
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
        
        $autorizacion =  $this->autorizacionesService->find($id);
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

        $expediente = $this->expedienteService->findWithFilter([
            'expid' => $autorizacion->asignacion->asigexp_id       
        ]);
        if($request->has('estado') and $request->vista == 'autorizaciones'){
            $autorizaciones = $this->autorizacionesService->index($request);     
            $view = view('myforms.frm_autorizaciones_list_ajax',compact('autorizaciones'))->render();
        }else{
            $view = view('myforms.components_exp.frm_autorizaciones_ajax',compact('expediente'))->render();
       
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
        //$asignacion = AsignacionCaso::find($autorizacion->asig_caso_id);

        $expediente = $this->expedienteService->findWithFilter([
            'expid' => $autorizacion->asignacion->asigexp_id       
        ]);
        $view = view('myforms.components_exp.frm_autorizaciones_ajax',compact('expediente'))->render();
        
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
 