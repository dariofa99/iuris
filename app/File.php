<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class File extends Model
{
    protected $table="files"; //el modelo se va a relacionar con la tabla
    protected $fillable=['hash','original_name','encrypt_name','path','size'];//que campos tiene la
    public $disk;
    
    public function setDisk($disk){
      $this->disk = $disk;
      return $this;
    }

   
       
     public function userinconciliacion(){
        return $this->belongsToMany(User::class,'conciliacion_has_files','file_id','user_id')
        ->withPivot('id','concepto','file_id','type_status_id','user_id')->withTimestamps(); 
     } 

     public function userinestado(){
      return $this->belongsToMany(User::class,'conciliacion_estados_files','file_id','user_id')
      ->withPivot('id','file_id','con_status_id','user_id','conciliacion_id')->withTimestamps(); 
   } 

}