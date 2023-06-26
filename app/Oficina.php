<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsigNotasExt;
class Oficina extends Model
{
    use AsigNotasExt;
    protected $table = 'oficinas';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','ubicacion'];

        public function users()
    {
       return $this->belongsToMany(User::class,'oficina_usuarios')
       ->withPivot('user_id','oficina_id')->withTimestamps();
    } 
    
    public function turnos()
    {
       return $this->hasMany(Turno::class,'trnid_oficina');
      
    } 

    public function sedes(){
        return $this->belongsToMany(Sede::class,'sede_oficinas','oficina_id','sede_id')
        ->withPivot('id','sede_id','oficina_id')->withTimestamps(); 
     } 
}
 