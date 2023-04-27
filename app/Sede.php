<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ConfigSede;
class Sede extends Model
{
    use ConfigSede;

    protected $table = 'sedes';
    protected $primaryKey = 'id_sede';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','ubicacion','horario_atencion'
    ];

        public function users() 
    {
       return $this->belongsToMany(User::class,'sedes_usuarios')
       ->withPivot('user_id','sedes_id')->withTimestamps();
    } 
    
     
    public function configuracions(){
        return $this->belongsToMany(Configuracion::class,'configuraciones_sede','sede_id','configuracion_id')
        ->withPivot('id','sede_id','configuracion_id')->withTimestamps(); 
     } 

     
     
}
 