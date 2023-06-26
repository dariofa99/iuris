<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Segmento extends Model
{
   protected $table = 'segmentos';

   protected $fillable = [
   	'segnombre',
   	'fecha_inicio',
   	'fecha_fin',
   	'estado',
    'perid',
    'act_fc',
    'fecha_corte',
    'est_evaluado',
   	'segusercreated',
   	'seguserupdated'
   ];

   public function periodo()
    {
       return $this->belongsTo('App\Periodo','perid','id');
    }


   public function getEstado(){

   	if ($this->estado) {
   		$estado = 'Activo';
   	}else{
   		$estado = 'Inactivo';
   	}
   	return $estado;
 
   }

   public function sedes(){
    return $this->belongsToMany(Sede::class,'sede_segmentos','segmento_id','sede_id')
    ->withPivot('id','sede_id','segmento_id')->withTimestamps(); 
 }

   public function scopeCriterio($query ,$data,$criterio)
    {
           if (trim($data) != '')
        {

            

            switch ($criterio) { 
                case 'name':
                   return $query->where('segnombre', $data); 
                    break;
                case 'fecha_ini':
                  return $query->whereDate('fecha_inicio', $data); 
                  break;
                case 'fecha_fin':
                  return $query->whereDate('fecha_fin', $data); 
                  break;
                                              
            }           
                     
        }

    }
}
