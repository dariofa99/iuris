<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    protected $table = 'autorizaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_estudiante','num_identificacion','doc_expedicion','num_carne','calidad_de',
        'tipo_proceso','num_radicado','juzgado','estado','asig_caso_id','user_solicitante_id',
        'user_aprobo_id','genero','fecha_autorizado'];

        
        public function asignacion()
    {
        return $this->belongsTo(AsignacionCaso::class, 'asig_caso_id', 'id');
    }
 
    public function sedes(){
        return $this->belongsToMany(Sede::class,'sede_autorizaciones','autorizacion_id','sede_id')
        ->withPivot('id','sede_id','autorizacion_id')->withTimestamps(); 
     }

    public function scopeSearch($query ,$request)
    {
           if (trim($request->data) != '')
        {
            switch ($request->tipo_busqueda) { 
                
                case 'num_radicado':
                    return $query->where('num_radicado', $request->data); 
                break; 
                case 'num_identificacion':
                    return $query->where('num_identificacion', $request->data); 
                break; 
                
            }           
            
            
        }

    }

}
