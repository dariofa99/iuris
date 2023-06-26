<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

use App\Traits\UploadFile; 

class CaseLog extends Model
{
   use UploadFile;
   public $disk = 'log_files';
    protected $table="case_logs"; //el modelo se va a relacionar con la tabla
    protected $fillable=['concept',
    'description',
    'type_log_id','user_id','exp_id','shared','notification_date','type_status_id'];//que campos tiene la
    protected $notification_type;

    public function user(){
        return $this->belongsTo(User::class,'user_id');      
    }    
     
     public function expediente(){
        return $this->belongsTo(Expediente::class,'exp_id');        
     }     
      
    public function type_log(){     
        return $this->belongsTo(TablaReferencia::class,'type_log_id');    
     }

  /*   public function type_category(){     
      return $this->belongsTo(TablaReferencia::class,'type_category_id');    
    } */

     
    /* public function files(){
        return $this->hasMany(LogFile::class,'case_log_id');
         
     }    */

     public function files(){
      return $this->belongsToMany(File::class,'caselog_has_files','caselog_id')
      ->withPivot('id','file_id','type_status_id')->withTimestamps(); 
   }  

   

    

}