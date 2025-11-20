<?php

namespace App;

use App\Traits\UploadFile;
use Illuminate\Database\Eloquent\Model;

class IncidenciaEstado extends Model
{
    use UploadFile;

    private $disk = 'incidencias_files';
    protected $table = 'incidencias_estado';
    protected $fillable = [
        'incidencia_id',
        'motivo',
        'user_id',
        'estado_id',
        'incidencia_id'
    ];

    public function estado()
    {
        return $this->belongsTo(TablaReferencia::class, 'estado_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function files(){
        return $this->belongsToMany(File::class,'incidencias_has_files','incidencia_id')
        ->withPivot('id','file_id','type_status_id','user_id','incidencia_id')->withTimestamps(); 
     } 
}
