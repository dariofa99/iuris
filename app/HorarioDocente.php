<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Expediente;
//use App\Traits\AsigNotas;

class HorarioDocente extends Model
{
   // use HorarioDocente;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'horario_docentes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
						    'docidnumber',                           
                            'horas_a',
                            'horas_b',
                            'num_max_est',
                            'num_est_a',
                            'num_est_b'
             			   ];



    public function docente(){
       return $this->belongsTo(User::class, 'docidnumber','idnumber');
    }


} 
 