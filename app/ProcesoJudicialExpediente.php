<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\UploadFile;


class ProcesoJudicialExpediente extends Model
{

    use UploadFile;
    protected $table = 'expediente_procesos';
    private $disk = 'exp_procjfiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha',
        'hora',
        'comentario',
        'estado_id',
        'asig_caso_id',
        'user_id',
        'file_id'
    ];

    public function estado()
    {
        return $this->belongsTo(TablaReferencia::class, 'estado_id', 'id');
    }
    public function files(){
        return $this->belongsToMany(File::class,'expprocesos_has_files','expproc_id')
        ->withPivot('id','file_id')->withTimestamps(); 
     }  

     public function asignacion()
    {
        return $this->belongsTo(AsignacionCaso::class, 'asig_caso_id', 'id');
    }

}
