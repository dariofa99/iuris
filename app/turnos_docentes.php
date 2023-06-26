<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class turnos_docentes extends Model
{
    protected $table = 'turnos_docentes';
     protected $fillable = ['trnd_docidnumber', 'trnd_dia', 'trnd_hora_inicio', 'trnd_hora_fin', 'trndid_periodo', 'trndusercreated', 'trnduserupdated', 'created_at', 'updated_at'];
}
