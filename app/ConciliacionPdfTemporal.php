<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Traits\UploadFile;
class ConciliacionPdfTemporal extends Model
{
    //use UploadFile;

    //public $disk = 'conciliacion_pdf_temporal';
    protected $table = 'conciliacion_pdf_temporal';

    protected $fillable = [        
        'parent_reporte_pdf_id',
        'conciliacion_id',
        'status_id',
        'reporte_pdf_id'
    ];

    public function conciliaciones()
    {
       return $this->hasMany(ConciliacionReporte::class,'conciliacion_id');
    } 

    public function reporte_parent()
    {
       return $this->belongsTo(PdfReporte::class,'parent_reporte_pdf_id');
    } 

    public function reporte_child()
    {
       return $this->belongsTo(PdfReporte::class,'reporte_pdf_id');
    } 
    
   
}
