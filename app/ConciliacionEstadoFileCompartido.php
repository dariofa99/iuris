<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ConciliacionEstadoFileCompartido extends Model
{


    
   protected $table = 'conc_report_compartido';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array 
     */
  
    protected $fillable = [
    'token',
    'fecha_exp_token',
    'conciliacion_id',
    'status_id',
    'clave',
    'category_id',
    'means_id'
   ];



    public function files(){
        return $this->belongsToMany(File::class,'conc_report_comp_files','conc_report_comp_id')
        ->withPivot('id','conc_report_comp_id','file_id')
        ->withTimestamps(); 
     } 

     public function downloads(){
      return $this->hasMany(ConciliacionEstadoReporteDescargado::class,'conc_report_comp_id'); 
   } 
   
   public function category(){
      return $this->belongsTo(TablaReferencia::class,'category_id'); 
   } 

   public function means(){
      return $this->belongsTo(TablaReferencia::class,'means_id'); 
   } 
}
 