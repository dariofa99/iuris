<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoCaso extends Model
{
    protected $table = 'estados_caso';

    protected $fillable = [
    	'comentario',
    	'useridnumber',
    	'expidnumber',
    	'ref_estado_id',
    	'ref_motivo_estado_id'
    ];



    public function user()    {
        return $this->belongsTo(User::class, 'useridnumber', 'idnumber');
    }

    public function estado()    {
        return $this->belongsTo(Estado::class, 'ref_estado_id', 'id');
    }

    public function motivo(){
        return $this->belongsTo(MotivoEstadoCaso::class, 'ref_motivo_estado_id', 'id');
    }
}
