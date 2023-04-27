<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogSession extends Model
{
    protected $table="log_sessions"; //el modelo se va a relacionar con la tabla
    protected $fillable=['user_id'];//que campos tiene la


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
