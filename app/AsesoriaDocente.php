<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsesoriaDocente extends Model
{

    protected $table = 'asesorias_docente';

    protected $fillable = [
    	'comentario',
        'estado',
        'apl_shared',
    	'estidnumber',
    	'docidnumber',
    	'expidnumber'
    ];

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estidnumber', 'idnumber');
    }
    public function docente()
    {
        return $this->belongsTo(User::class, 'docidnumber', 'idnumber');
    }

}
