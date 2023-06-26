<?php

namespace App\Http\Controllers;

use App\Conciliacion;
use App\ConciliacionEstado;
use App\ConciliacionEstadoFileCompartido;
use App\ConciliacionEstadoReporteDescargado;
use App\ConciliacionEstadoReporteGenerado;
use App\ConciliacionPdfTemporal;
use App\ConciliacionReporte;
use App\Mail\ConfirmarFirma;
use App\PdfReporte;
use App\User;
use App\PdfReporteDestino;
use App\Traits\PdfReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Redirect;


class ConciliacionesFirmasController extends Controller
{
 use PdfReport;

   
    public function firmaVerify(Request $request,$token){  
      
      try {
        $user = DB::table('pdf_reportes_users')       
        ->where('pdf_reportes_users.token',$token)
        ->first();
        $nowDate = Carbon::now();
        $diff = $nowDate->diffInDays($user->created_at);       
         if($user and $diff < 4){
            if($user->firmado == 1)  return view("myforms.conciliaciones_firmas.firmar_firmado");
            return view("myforms.conciliaciones_firmas.verificar_codigo",compact('token'));
        }
      } catch (\Throwable $th) {
       
      }
        
        abort(303);
       // $user->sedes;
        //dd($user->sedes[0]->id_sede);
        
    }

    public function tokenVerify (Request $request){
       // dd($request->all());
        $user = User::with('tipo_pdf_firmante')
        ->where('users.idnumber',$request->idnumber)
        ->whereHas('tipo_pdf_firmante', function($query) use ($request) {
            $query->where('pdf_reportes_users.token',$request->token_firma)
            ->where('pdf_reportes_users.codigo',$request->codigo);
        })        
        ->first();
        if($user){
            return Redirect::route('firmar.accept',['token_firma'=>$request->token_firma,
            'codigo'=>$request->codigo]);            
        }
        return Redirect::back()->with('message-danger','No existe el registro')
        ->withInput($request->input());  
       // return view("myforms.conciliaciones_firmas.verificar_codigo",compact('token','user'));
        
    }

    public function showFirmaAccept (Request $request){
        
      //  dd($request->all()); 
        $user = DB::table('pdf_reportes_users')      
        ->where('pdf_reportes_users.codigo',$request->codigo)
        ->where('pdf_reportes_users.token',$request->token_firma)
        ->first();
       
        if($user){
            if($user->firmado == 1)  return view("myforms.conciliaciones_firmas.firmar_firmado");
            $pdf_report = PdfReporteDestino::find($user->pdf_reporte_id);   
            //dd($user);           
            return view("myforms.conciliaciones_firmas.firmar_documento",compact('pdf_report','user'));
        }
        abort(303);
        return view("myforms.conciliaciones_firmas.verificar_codigo",compact('token','user'));
        
    }

    public function firmaAccept (Request $request){
        
        // dd($request->all());
 
         $user = User::with('tipo_pdf_firmante')
         ->where('users.email',$request->email)
         ->whereHas('tipo_pdf_firmante', function($query) use ($request) {
             $query->where('pdf_reportes_users.token',$request->token_firma)
             ->where('pdf_reportes_users.codigo',$request->codigo);
         })        
         ->first();
         //dd($user);
         if($user){
            $user->token = $request->token_firma;
            $user->codigo = $request->codigo;
            Mail::to($user)->send(new ConfirmarFirma($user));
            return response()->json(['user'=> $user ], 200, ['Acept']);
             
         }
         return response()->json(['user'=> $user ], 404, ['Acept'=>'HttpResponse']);
       
         
     }

     public function firmaConfirm (Request $request,$token,$codigo){
        
        //dd($request->all());
 
        $user = DB::table('pdf_reportes_users')      
        ->where('pdf_reportes_users.codigo',$codigo)
        ->where('pdf_reportes_users.token',$token)
        ->first();
        if($user){
            $user = DB::table('pdf_reportes_users')      
            ->where('pdf_reportes_users.codigo',$codigo)
            ->where('pdf_reportes_users.token',$token)
            ->update([
              'firmado'=>1,
              'fecha_firma'=>date("Y-m-d H:i:s")]);     
            return view("myforms.conciliaciones_firmas.firmar_firmado");
             
         }
         abort(303);
       
         
     }

     public function firmaRevocar (Request $request,$token,$codigo){     
     request()->merge(['codigo' => $codigo,'token' => $token ]);
     $user = DB::table('pdf_reportes_users')      
      ->where('pdf_reportes_users.codigo',$codigo)
      ->where('pdf_reportes_users.token',$token)
      ->where('pdf_reportes_users.revocado',0)
      ->first();      
      if($user){
        $pdf_report = PdfReporteDestino::find($user->pdf_reporte_id);   
        return view("myforms.conciliaciones_firmas.firmar_revocar",compact('pdf_report','user'));
      }
      abort(303);

           
       
   }

   public function firmaRevocarOk(Request $request){

    $user = DB::table('pdf_reportes_users')      
    ->where('pdf_reportes_users.codigo',$request->codigo)
    ->where('pdf_reportes_users.token',$request->token_firma)
    ->first();
    if($user){
        $user = DB::table('pdf_reportes_users')      
        ->where('pdf_reportes_users.codigo',$request->codigo)
        ->where('pdf_reportes_users.token',$request->token_firma)
        ->update(['revocado'=>1]);     
        return response()->json($user);
         
     }
     return response()->json($user);
   }

   public function getFirmaRevocar(Request $request){

    $user = DB::table('pdf_reportes_users')      
    ->where('pdf_reportes_users.codigo',$request->codigo)
    ->where('pdf_reportes_users.token',$request->token_firma)
    ->first();
   
     return response()->json($user);
   }

     public function showFormVerifyDocument($token,Request $request){
        
      // dd("ss");
          $conGen = ConciliacionEstadoFileCompartido::where('token',$token)
          ->first();
         
         
 
          if($conGen){
              if(Carbon::now() < $conGen->fecha_exp_token){
                  
                return view("myforms.conciliaciones_firmas.consultar_documento",compact('token','conGen'));
         
              }
          }
         
          abort(303);
          return view("myforms.conciliaciones_firmas.verificar_codigo",compact('token','user'));
          
      }

      public function showVerifyDocument($token,Request $request){      
        $conGen = ConciliacionEstadoFileCompartido::where('token',$token)
        ->first();       
          if($conGen){
            if(Carbon::now() < $conGen->fecha_exp_token and Carbon::now() < session("newDateTime") ){                
              return view("myforms.conciliaciones_firmas.ver_documentos",compact('token','conGen'));       
            }
        }       
        return redirect("/firmar/pdf/verify/".$token);
        
    }

      public function storeReportDescargado(Request $request){
        $conGen = ConciliacionEstadoFileCompartido::where('token',$request->token)
        ->where('clave',$request->clave)
        ->first();  
        if ($conGen and Carbon::now() < $conGen->fecha_exp_token) {
           $rep_desc = ConciliacionEstadoReporteDescargado::create([
            'conc_report_comp_id'=>$conGen->id,
            'data'=>json_encode($request->all())]);
             $newDateTime = Carbon::now()->addHours(1);

            return redirect()
            ->route("show.documents", 
                                [
                            'token' => $request->token]
            )     
            ->with('newDateTime', $newDateTime);
        }
        return redirect()->back()->withInput()->with('status', "Datos no encontrados");;
        
        
      }

      public function getStatus(Request $request){

        $user = DB::table('pdf_reportes_users')       
        ->where('pdf_reportes_users.token',$request->token_firma)
        ->where('pdf_reportes_users.codigo',$request->codigo)
        ->first();
      //  return response()->json(['firmado'=> 0,'request'=>$user ], 200); 
         if($user){
          return response()->json(['firmado'=> $user->firmado,'request'=>$request->all() ], 200);            
        }
        return response()->json(['firmado'=> 0,'request'=>$request->all() ], 200);            

      }
 
}
