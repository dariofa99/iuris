<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ConciliacionUserForm extends Model 
{ 
    protected $table="conciliacion_user_form"; //el modelo se va a relacionar con la tabla
    protected $fillable=['parte','reference_data_id'];//que campos tiene la




}
