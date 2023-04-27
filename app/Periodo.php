<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'periodo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
						    'prdfecha_inicio',
                            'prdfecha_fin',
						    'prddes_periodo',
                            'estado',
						    'prdusercreated',
                            'prduserupdated'
                    	   ];


public function getEstado(){

    if ($this->estado) {
        $estado = 'Activo';
    }else{
        $estado = 'Inactivo';
    }
    return $estado;

   }
   public function sedes(){
    return $this->belongsToMany(Sede::class,'sede_periodos','periodo_id','sede_id')
    ->withPivot('id','sede_id','periodo_id')->withTimestamps(); 
 }
   public function scopeCriterio($query ,$data,$criterio)
    {
           if (trim($data) != '')
        {

            

            switch ($criterio) { 
                case 'name':
                   return $query->where('prddes_periodo', $data); 
                    break;
                case 'fecha_ini':
                  return $query->whereDate('prdfecha_inicio', $data); 
                  break;
                case 'fecha_fin':
                  return $query->whereDate('prdfecha_fin', $data); 
                  break;
                                              
            }           
                     
        }

    }
                           

}
 