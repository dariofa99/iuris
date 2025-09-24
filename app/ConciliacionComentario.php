<?php

namespace App;

use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Model;
class ConciliacionComentario extends Model
{
    use UploadFile;
    
    protected $table = 'conciliaciones_comentarios';
    private $disk = 'coment_files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comentario','user_id','conciliacion_id','compartido','asunto','reporte_id'];

    public function user(){ 
         return $this->belongsTo(User::class,'user_id');
    } 
    public function type_status(){     
        return $this->belongsTo(TablaReferencia::class,'type_status_id');    
     }
     public function conciliacion(){     
        return $this->belongsTo(Conciliacion::class,'conciliacion_id');    
     }

      public function files()
     {
        return $this->belongsToMany(File::class,'conc_coment_has_files','comentario_id')
        ->withPivot('comentario_id','file_id')->withTimestamps();
     }
}
 