<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsigNotasExt;
use App\Traits\UploadFile;
class ConciliacionEstado extends Model
{
   use UploadFile;

   public $disk = 'conc_status_files';
    
   protected $table = 'conciliaciones_estados';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array 
     */
    protected $fillable = [
        'concepto','type_status_id','user_id','conciliacion_id'];

    public function user(){
         return $this->belongsTo(User::class);
    } 
    public function type_status(){     
        return $this->belongsTo(TablaReferencia::class,'type_status_id');    
     }
     public function conciliacion(){     
        return $this->belongsTo(Conciliacion::class,'conciliacion_id');    
     }

     public function files()
     {
        return $this->belongsToMany(File::class,'conciliacion_estados_files','con_status_id')
        ->withPivot('conciliacion_id','con_status_id','file_id')->withTimestamps();
     } 

}
 