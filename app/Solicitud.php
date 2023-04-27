<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\UploadFile;
class Solicitud extends Model
{
    use UploadFile;
    protected $table = 'solicitudes';
    public $disk = 'solicitud_files';

     protected $fillable = [
        'number','idnumber', 'estrato_id','tipodoc_id','name','lastname','tel1','tiempo_espera',
        'description','type_status_id','turno','type_category_id','token','mensaje','date_time'
    ];

    public function estado()
    {
        return $this->belongsTo(TablaReferencia::class, 'type_status_id', 'id');
    }
    public function categoria()
    {
        return $this->belongsTo(TablaReferencia::class, 'type_category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idnumber', 'idnumber');
    }

    public function files(){
        return $this->belongsToMany(File::class,'solicitud_has_files','solicitud_id')
        ->withPivot('id','file_id','user_id','type_status_id','solicitud_id','concept','type_category_id')->withTimestamps(); 
     } 

     
    public function sedes(){
        return $this->belongsToMany(Sede::class,'sede_solicitudes','solicitud_id','sede_id')
        ->withPivot('id','sede_id','solicitud_id')->withTimestamps(); 
     } 
     
     public function expedientes(){
        return $this->belongsToMany(Expediente::class,'solicitud_has_exp','solicitud_id','exp_id')
        ->withPivot('solicitud_id','exp_id')->withTimestamps(); 
     } 
  
  
}
