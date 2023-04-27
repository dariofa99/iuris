<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;


class OrigenNota extends Model
{
 
    
     protected $table = 'origen_notas';
     protected $fillable = ['orgntsnombre'];



   

    public function nota()
    {
        return $this->hasMany(Nota::class);
    }

    
 }


