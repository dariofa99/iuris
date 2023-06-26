<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Traits\ConfigSede;
class Configuracion extends Model
{
    //use ConfigSede;
    protected $table = 'configuraciones';

    protected $fillable = [
        'nombre_corto',
        'nombre_largo',
        'descripcion'
    ];  
  
      
    public function sedes(){   
        return $this->belongsToMany(Sede::class,'configuraciones_sede','configuracion_id','sede_id')
        ->withPivot('id','sede_id','configuracion_id')->withTimestamps(); 
     } 

    
    
}
