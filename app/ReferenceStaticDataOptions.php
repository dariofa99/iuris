<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferenceStaticDataOptions extends Model
{
    protected $table="references_static_data_options"; //el modelo se va a relacionar con la tabla
    protected $fillable=['value','status','active_other_input','references_data_id'];//que campos tiene la

    


}
