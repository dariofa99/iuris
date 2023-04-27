<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadFile;
class PdfReporte extends Model
{
    use UploadFile;

    public $disk = 'pdf_reporte_files'; 
    protected $table = 'pdf_reportes';

    protected $fillable = [
        'reporte',
        'report_keys',
        'nombre_reporte',
        'configuraciones',
        'is_copy',
        'categoria_id'
    ];

    public function aditional_data()
    {
        return $this->hasMany(PdfReporteAditionalData::class, 'reporte_id', 'id');
    }

    public function destino()
    {
       return $this->belongsTo(PdfReporteDestino::class,'id','reporte_id');
    } 
    
    public function getConfig()
    {
        $configuraciones = json_decode($this->configuraciones);
       return $configuraciones;
    } 

    public function getPdfConfig($seccion)
    {
        $configuraciones = $this->files()->where('seccion',$seccion)->first();
        if($configuraciones){
            $configuracion =  json_decode($configuraciones->pivot->configuracion);    
            $configuracion->imagen = "/storage/".$configuraciones->path;   
          // dd($configuracion) ;
           return $configuracion;
        }        
       return null;
    }  

    public function files(){
        return $this->belongsToMany(File::class,'pdf_reportes_has_files','pdf_reporte_id')
        ->withPivot('id','pdf_reporte_id','seccion','file_id','configuracion')->withTimestamps(); 
     } 

     public function getDataValWShort($short_name){
        $ref_data = $this->aditional_data()
        ->join('references_data','references_data.id','pdf_report_personalized_values.reference_data_id')
       // ->join('referencias_tablas','referencias_tablas.id','estadocivil_id')
        ->where(['short_name'=>$short_name])->first();
           //dd($ref_data)     ;
        if($ref_data){
            return $ref_data;
        }

       return false;
    }
   
}
