<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\ColorTurnos;

class Turno extends Model
{
    use ColorTurnos;
    
     protected $table = 'turnos';
     protected $fillable = ['trnid_estudent','trnid_color','trnid_horario','trnid_periodo','trnid_dia','trnid_oficina'];



   public function turnosestudiantes()
    {
        return $this->belongsTo(User::class,'trnid_estudent','idnumber');
        //->join('sede_usuarios','sede_usuarios.user_id','=','users.id'); 
    }
    public function estudiante()
    {
        return $this->belongsTo(User::class,'trnid_estudent','idnumber');
    }


    public function oficina()
    {
        return $this->belongsTo(Oficina::class,'trnid_oficina','id');
    }

    public function color() 
    {
        return $this->belongsTo(TablaReferencia::class,'trnid_color');
    }

    public function dia()
    {
        return $this->belongsTo(TablaReferencia::class,'trnid_dia');
    }

    public function horario()
    {
        return $this->belongsTo(TablaReferencia::class,'trnid_horario');
    }
 }


