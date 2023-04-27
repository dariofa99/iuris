<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'data',
    	'host',
    	'exp_id',
        'user_id'
    ];



     public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function expediente(){
    return $this->belongsTo(Expediente::class, 'exp_id','id');
   } 

}
