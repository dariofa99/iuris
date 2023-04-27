<?php

namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;
use Storage;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Biblioteca; 
//use Yajra\Datatables\Datatables;

class BibliotecaController extends Controller
{

  public function __construct(){
    Carbon::setlocale('es');
  } 
 

    public function index(Request $request){ 
      array_map('unlink', glob(public_path('act_temp/'.currentUser()->id.'___*')));//elimina los archivos que el 
      //$bibliotecas = Biblioteca::where('bibliestado',1)->orderBy('created_at','desc')->get();

      $bibliotecas = Biblioteca::join('sede_bibliotecas as sb','sb.biblioteca_id','=','bibliotecas.id')
      ->where("sede_id",session('sede')->id_sede)
      ->where('bibliestado',1)
      ->Criterio($request)->orderBy('bibliotecas.created_at','desc')
      ->get();


       $active_galeria = 'active';

      return view('galeria.index',compact('bibliotecas','active_galeria'));
    }

    public function showBibliotecaOff(Request $request){
       
     // $bibliotecas = Biblioteca::where('bibliestado',0)->orderBy('created_at','DESC')->get();
      $bibliotecas = Biblioteca::join('sede_bibliotecas as sb','sb.biblioteca_id','=','bibliotecas.id')
      ->where("sede_id",session('sede')->id_sede)
      ->where('bibliestado',0)->Criterio($request)->orderBy('bibliotecas.created_at','desc')->get();

      $active_galeria = 'active';
      return view('galeria.show_biblioteca_inactiva',compact('bibliotecas','active_galeria'));
    }

   public function show(){

   }

   public function create(){
       $active_galeria = 'active';
       return view('galeria.create',compact('active_galeria'));

    }

    public function store(Request $request){
       //dd('hola');
      if ($request->ajax()) {
        if($request->file('doc_file')!=''){
                $docum=$request->file('doc_file');
                $file_route= time()."_".$docum->getClientOriginalName();
                Storage::disk('files_bibliotecas')->put($file_route, file_get_contents($docum->getRealPath() ) );
                $biblidocnomgen =$file_route;
                $biblidocnompropio =$docum->getClientOriginalName();
                $biblidocruta =Storage::disk('files_bibliotecas')->url($file_route);
               
                $biblioteca = Biblioteca::create([
                  'biblinombre'=>$request['biblinombre'],
                  'biblidescrip'=>$request['biblidescrip'],
                  'bibliid_ramaderecho'=>$request['bibliid_ramaderecho'],
                  'bibliid_tipoarchivo'=>$request['bibliid_tipoarchivo'],
                  'biblidocnompropio'=>$biblidocnompropio,
                  'biblidocnomgen'=>$biblidocnomgen,
                  'biblidocruta'=>$biblidocruta,
                  'biblidoctamano'=>$docum->getSize(),
                  'bibliusercreated'=>currentUser()->idnumber,
                  'bibliuserupdated'=>currentUser()->idnumber,
                ]);  
                if(session()->has('sede')){
                  $biblioteca->sedes()->attach(session('sede')->id_sede);
                } 
                //$actdocruta = public_path($url);               
                         
        }
           return response()->json($docum->getSize());
      }
       
       //dd($request->file('doc_file')->getMimeType());
      

    }

    
   
    public function edit($id){
      if (\Request::ajax()) {
        $biblioteca= Biblioteca::find($id);  
        $biblioteca->rama_derecho;
        $biblioteca->categoria;
        $biblioteca->user;   
        $biblioteca->user_update;   
        return response()->json($biblioteca);
      }
      
    }

    public function update(Request $request){
      if (\Request::ajax()) {
        $biblioteca = Biblioteca::find($request->biblioteca_id); 
        $biblioteca->fill($request->all()); 
        $biblioteca->bibliuserupdated = \Auth::user()->idnumber;         
         if($request->file('doc_file')!=''){                 
            if ($biblioteca->biblidocruta!='') {
            Storage::delete($biblioteca->biblidocruta);
            $docum=$request->file('doc_file');
            $file_route = time()."_".$docum->getClientOriginalName();
            Storage::disk('files_bibliotecas')->put($file_route, file_get_contents($docum->getRealPath() ) );
                $biblidocnomgen =$file_route;
                $biblidocnompropio =$docum->getClientOriginalName();
                $biblidocruta =Storage::disk('files_bibliotecas')->url($file_route);
                $biblioteca->biblidocnomgen = $file_route;
                $biblioteca->biblidocnompropio = $biblidocnompropio;
                $biblioteca->biblidocruta = $biblidocruta;
                $biblioteca->biblidoctamano = $docum->getSize();
                
            }
          }
        $biblioteca->save();        // 
       return response()->json($biblioteca);
      }
        //dd($request);
    }

    public function change($id){
      $biblioteca= Biblioteca::find($id);
      if ($biblioteca->bibliestado == 1) {
        $biblioteca->bibliestado = 0;
        $biblioteca->save();
        return redirect('/bibliotecas');
      }elseif($biblioteca->bibliestado == 0){
        $biblioteca->bibliestado = 1;
        $biblioteca->save();
        return redirect('bibliotecas/inactivas/view');
      }
      
      
    }
    public function bibliodowpdf($id){
      $biblioteca= Biblioteca::find($id);
      $url = 'app/files_bibliotecas/'.$biblioteca->biblidocnomgen;
      $rutaDeArchivo = storage_path($url);
      $filename = currentUser()->id.'___'.$biblioteca->biblidocnomgen;
      copy( $rutaDeArchivo, public_path("act_temp/".$filename));

                return redirect("act_temp/".$filename);
    }

    
}
