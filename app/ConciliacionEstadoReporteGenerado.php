<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConciliacionEstadoReporteGenerado extends Model
{


    
   protected $table = 'conc_est_report_generado';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array 
     */
  
    protected $fillable = [
    'fecha_exp_token',
    'conciliacion_id',
    'status_id',
    'reporte_id',
    'clave'
   ];



 /*    public function files(){
        return $this->belongsToMany(File::class,'conc_report_compartido','conc_estreportgen_id')
        ->withPivot('id','conc_estreportgen_id','file_id')->withTimestamps(); 
     } 

     public function downloads(){
      return $this->hasMany(ConciliacionEstadoReporteDescargado::class,'conc_estreportgen_id'); 
   }  */
   

}
 