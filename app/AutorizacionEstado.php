<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class AutorizacionEstado extends Model
{
    protected $table = 'autorizaciones_has_estados';

    //crear un atributo fillable para los campos de la tabla
    protected $fillable = [
        'autorizacion_id',
        'estado_id',
        'comentario',
        'user_id'
    ];  
    //crear un atributo que devuelva el created_at en formato d-m-Y H:i:s
    protected $appends = ['created_at_formatted'];
    public function getCreatedAtFormattedAttribute()
    {
        return getSmallDateWithHour($this->created_at);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estado()
    {
        return $this->belongsTo(TablaReferencia::class, 'estado_id');
    }
}