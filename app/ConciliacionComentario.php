<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsigNotasExt;
class ConciliacionComentario extends Model
{
    
    protected $table = 'conciliaciones_comentarios';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comentario','user_id','conciliacion_id','compartido'];

    public function user(){
         return $this->belongsTo(User::class,'user_id');
    } 
    public function type_status(){     
        return $this->belongsTo(TablaReferencia::class,'type_status_id');    
     }
     public function conciliacion(){     
        return $this->belongsTo(Conciliacion::class,'conciliacion_id');    
     }
}
 