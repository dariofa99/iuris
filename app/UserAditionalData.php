<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAditionalData extends Model
{
    protected $table = 'user_aditional_data';
    protected $fillable = ['value',
    'value_is_other',
    'reference_data_id',
    'reference_data_option_id',
    'user_id'];


    public function reference()
    {
       return $this->belongsTo(ReferencesData::class,'reference_data_id','id');
    } 

}
