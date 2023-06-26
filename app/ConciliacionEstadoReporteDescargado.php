<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConciliacionEstadoReporteDescargado extends Model
{


    
   protected $table = 'conc_report_descargado';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array 
     */
  
    protected $fillable = [
    'conc_report_comp_id','data'];

    
   

}
 