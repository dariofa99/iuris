<?php

namespace App\Http\Controllers;

use App\Conciliacion;
use App\ConciliacionPdfTemporal;
use App\ConciliacionReporte;
use App\PdfReporte;
use App\PdfReporteDestino;
use App\Traits\PdfReport as PdfTrait;
use Illuminate\Database\Eloquent\Builder;
use PDF;
use Illuminate\Http\Request;
use Storage;

//use Illuminate\Http\Response;

class PdfReportesController extends Controller
{
    use PdfTrait;

    public function loadPdf($conciliacion,$reporte,$estado=null){
        //dd("");
        $conciliacion = Conciliacion::find($conciliacion);
        $reporte = PdfReporte::find($reporte);
       // dd($conciliacion);
        $pdf_temp = ConciliacionPdfTemporal::where(function ($query) use ($conciliacion,$reporte,$estado){
            if($estado==null){
                return $query->where([
                    'conciliacion_id'=>$conciliacion->id,
                    'parent_reporte_pdf_id'=>$reporte->id
                ]);
                
            }else{
                return $query->where([
                    'conciliacion_id'=>$conciliacion->id,
                    'status_id'=>$estado,
                    'parent_reporte_pdf_id'=>$reporte->id
                ]);
            }           
        })->first(); 
        if($pdf_temp){
            $reporte =  $pdf_temp->reporte_child;
            $reporte->is_temp = true;
        }
        $cadena = '';
       if($reporte){
        $config = json_decode($reporte->configuraciones);  
        //dd($config->margin_string);
        $file_pie = $reporte->files()->where('seccion','pie')->first();
        $pie_conf = $file_pie != null ? json_decode($file_pie->pivot->configuracion) : null;            
        
        $file_enc = $reporte->files()->where('seccion','encabezado')->first();
        $encab_conf = $file_enc != null ? json_decode($file_enc->pivot->configuracion) : null;            
       
        $bodytag =  $this->getBody($reporte,$conciliacion);
       
        $pdf = PDF::loadView('pdf.conciliacion', 
        [
            'pdf'=> $bodytag,
            'margin'=>$config->margin_string,
            'pie'=>$file_pie,  
            'pie_conf'=>$pie_conf,
            'encabezado'=>$file_enc,
            'encab_conf'=>$encab_conf
        ])
        ->setPaper($config->tipo_papel);
        $path = public_path('/pdf_temp');
        $fileName =  time().'.'. 'pdf' ;
        $pdf->save($path . '/' . $fileName);
      
        return redirect('/pdf_temp' . '/' . $fileName)  ;
        //return $pdf->stream('conciliacion.pdf'); 
    // echo ($cadena);
       // dd($bodytag);
    }
    
   

    }



    public function loadPdfPreview(Request $request){
      //  return dd($request->all());
        array_map('unlink', glob(public_path('pdf_temp/*')));//elimina los archivos que el 
        $reporte = PdfReporte::find($request->id);
        if($request->reporte){
        $conciliacion = null;
        if($request->has('conciliacion_id')) $conciliacion = Conciliacion::find($request->conciliacion_id);
       if($reporte){
        $file_pie = $reporte->files()->where('seccion','pie')->first();
        $pie_conf = $file_pie != null ? json_decode($file_pie->pivot->configuracion) : null;          
        $file_enc = $reporte->files()->where('seccion','encabezado')->first();
        $encab_conf = $file_enc != null ? json_decode($file_enc->pivot->configuracion) : null;      
       }else{
        $reporte = new PdfReporte();
        $reporte->report_keys=$request->report_keys;
        $reporte->reporte = $request->reporte;
        $file_pie =null;
        $pie_conf = null;          
        $file_enc = null;
        $encab_conf = null;      
       }
        
        $bodytag = $this->getBody($reporte,$conciliacion);   
        $config = $this->setConfig($request)     ;
      //  dd($file_enc)    ;
        $pdf = \PDF::loadView('pdf.conciliacion', [
            'pdf'=> trim($bodytag),
            'margin'=>$config['margin_string'],
            'pie'=>$file_pie, 
            'pie_conf'=>$pie_conf,
            'encabezado'=>$file_enc,
            'encab_conf'=>$encab_conf
            ])
        ->setPaper($config['tipo_papel']);
        $path = public_path('/pdf_temp');
        $fileName =  time().'.'. 'pdf' ;
        $pdf->save($path . '/' . $fileName);
        return response()->json([
            'url'=>'/pdf_temp' . '/' . $fileName,
            
        ]);    
    }
    }
    public function editAsignacionReporte(Request $request){        
        $asignacionReporte = PdfReporteDestino::where($request->except(['_']))->get();
        return response()->json($asignacionReporte);
    }

    public function asignarReporte(Request $request){
        //return response()->json($request->all());
        $asignacionReporte = PdfReporteDestino::where([
            'tabla_destino'=>$request->tabla_destino ,
            'status_id'=>$request->status_id     ,
            'categoria'=>$request->has('categoria') ? $request->categoria : 'sin_categoria',       
       ])->delete();
       if($request->reporte_id and count($request->reporte_id)>0){
        foreach ($request->reporte_id as $reporte_id) {
            $asignacionReporte = PdfReporteDestino::create([
                'categoria'=>$request->has('categoria') ? $request->categoria : 'sin_categoria',
                 'tabla_destino'=>$request->tabla_destino,
                 'status_id'=>$request->status_id,
                 'reporte_id'=>$reporte_id
            ]);
         }
       }
    
       
        return response()->json($asignacionReporte);
    }
    public function create(){
        
        $conciliaciones = Conciliacion::all();
        $reportes = PdfReporte::where('is_copy',0)
        ->get();;
        $conciliacion = new Conciliacion();       
        return view('myforms.summernote_reportes.edit',compact('conciliaciones','reportes','conciliacion'));
   }
    public function store(Request $request){
      //  dd($request->all());
        $config = $this->setConfig($request); 
        $request['configuraciones'] = json_encode($config);
        $co_reporte = PdfReporte::create($request->all());
        if($request->file('encabezado_file')!=''){  
            $configuracion = json_encode($this->setEncaConfig($request)); 
            //$request['configuraciones'] = json_encode($configuracion);     
            $file = $co_reporte->uploadFile($request->file('encabezado_file'),'/pdf_reportes_'.$co_reporte->id);
            $co_reporte->files()->attach($file,[
                'seccion'=>'encabezado' ,
                'configuracion'=>$configuracion           
                ]);                    
		}
        if($request->file('pie_file')!=''){     
            $configuracion = json_encode($this->setPieConfig($request));        
            $file = $co_reporte->uploadFile($request->file('pie_file'),'/pdf_reportes_'.$co_reporte->id);
            $co_reporte->files()->attach($file,[
                'seccion'=>'pie',
                'configuracion'=>$configuracion                  
                ]);                   
		}
        return response()->json($co_reporte);
    }
 
    public function update(Request $request,$id){
      //  return $request->all();
        $config = $this->setConfig($request);
        $request['configuraciones'] = json_encode($config);
        $request['categoria_id'] = $request->has("categorianew_id") ? $request->get("categorianew_id"):$request->get("categoria_id");
        $co_reporte = PdfReporte::find($id); 
        $co_reporte->fill($request->all());
        $co_reporte->save();

        $file_en = $co_reporte->files()->where('seccion','encabezado')->first();
        $configuracion = json_encode($this->setEncaConfig($request));
        if($file_en){            
            $file_en->pivot->configuracion =  $configuracion ;
            $file_en->pivot->save();
        }        
        if($request->file('encabezado_file')!=''){          
           if($file_en and $file_en->path!=''){
            Storage::delete("public/".$file_en->path);
        }
        if($file_en) $file = $file_en->delete();                            
            $file = $co_reporte->uploadFile($request->file('encabezado_file'),'/pdf_reportes_'.$co_reporte->id);
            $co_reporte->files()->attach($file,[
                'seccion'=>'encabezado',
                'configuracion'=>$configuracion          
                ]);                    
		}
        $file_pie = $co_reporte->files()->where('seccion','pie')->first();
        $configuracion = json_encode($this->setPieConfig($request));
        if($file_pie){           
            $file_pie->pivot->configuracion =  $configuracion ;
            $file_pie->pivot->save();
        }             
        if($request->file('pie_file')!=''){              
            if($file_pie and $file_pie->path!=''){
             Storage::delete("public/".$file_pie->path);                  
         }
         if($file_pie) $file = $file_pie->delete();                  
            $file = $co_reporte->uploadFile($request->file('pie_file'),'/pdf_reportes_'.$co_reporte->id);
            $co_reporte->files()->attach($file,[
                'seccion'=>'pie',
                'configuracion'=>$configuracion                  
                ]);                   
		}
        return response()->json($co_reporte);
    }

    public function edit($id){
        $co_reporte = PdfReporte::find($id);
        $config = json_decode($co_reporte->configuraciones);  
        $co_reporte->configuraciones = $config;
        $co_reporte->files->each(function($file){
            //$rutaDeArchivo = storage_path("app/".$file->path);
           // copy( $rutaDeArchivo, public_path("act_temp/".$file->original_name));
            $file->temp_path = url(("storage/".$file->path));
            $file->pivot->configuracion = json_decode($file->pivot->configuracion);
        });
        return response()->json($co_reporte);
    }
   
    public function getReportes(Request $request){
        
      //  return response()->json($request->all());

        $reportes = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
            $query->where('is_copy', 0);
        })
            /* ->whereHas('temporales', function (Builder $query) use ($request) {
                $query->where('conciliacion_id', $request->conciliacion_id);
            }) */
            //    ->with('users')
            ->where($request->except(['_', 'conciliacion_id', 'conc_estado_id']))
           // ->where('categoria',$request->categoria)
            ->get();
       if(count($reportes)>0){
            $conciliacion = Conciliacion::find($request->conciliacion_id);
            $reporte = PdfReporte::find($reportes[0]->reporte_id);
        // return response()->json($reporte);
            $bodytag =  $this->getBody($reporte,$conciliacion);
        // $view = view('myforms.conciliaciones.documentos_cuerpo_correo',compact('bodytag'))->render();
            return response()->json([
                'body'=> $bodytag,   
                'reporte'=>$reporte      
            ]);
       }
       
       return response()->json([
        'error'=> "No hay modelos registrados",            
    ]);
    }

    public function getReportesByCategory(Request $request){
        
       //  return response()->json($request->all());
 
         $reportes = PdfReporte::where('is_copy', 0)
           ->where($request->all())
             ->get();

               

        if(count($reportes)>0){
            return response()->json($reportes);
        }
        
        return response()->json([
         'errors'=> ["No hay modelos registrados"],            
     ]);
     }

     public function getDestinosForReport(Request $request){
        
        //  return response()->json($request->all());
  
          $reportes = PdfReporte:: whereHas('destino', function (Builder $query) use($request) {
             $query->where($request->all());
         })->
         where('is_copy', 0)
            //->where($request->all())
              ->get();
 
                   
 
         if(count($reportes)>0){
             return response()->json($reportes);
         }
         
         return response()->json([
          'errors'=> ["No hay modelos registrados"],            
      ]);
      }

}
