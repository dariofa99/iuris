<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Traits\UploadFile;


class ExpedientePausas extends Model
{

   
    protected $table = 'expedientes_pausa';
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_inicial',
        'fecha_final',
        'userestud_id',        
        'asig_caso_id',
        'user_id',
        'estado_id'       
    ];

        
   
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'userestud_id', 'id');
    }
  

     public function asignacion()
    {
        return $this->belongsTo(AsignacionCaso::class, 'asig_caso_id', 'id');
    }

}
