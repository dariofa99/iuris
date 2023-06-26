<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConciliationAditionalStaticData extends Model
{
    protected $table = 'conciliacion_adst_data';
    protected $fillable = ['value',
    'value_is_other',
    'reference_data_id',
    'reference_data_option_id',
    'conciliacion_id'];


    public function reference()
    {
       return $this->belongsTo(ReferencesData::class,'reference_data_id','id');
    } 

}
