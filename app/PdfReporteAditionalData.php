<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PdfReporteAditionalData extends Model
{
    protected $table = 'pdf_report_personalized_values';
    protected $fillable = ['value',
    'value_is_other',
    'reference_data_id',
    'reference_data_option_id',
    'reporte_id'];


    public function reference()
    {
       return $this->belongsTo(ReferencesData::class,'reference_data_id','id');
    } 

}
