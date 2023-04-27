<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PdfReporteDestino extends Model
{


   
    protected $table = 'pdf_reportes_destinos';

    protected $fillable = [ 
        'tabla_destino',
        'reporte_id',
        'status_id',
        'categoria'
    ];

    public function reporte()
    {
       return $this->belongsTo(PdfReporte::class,'reporte_id');
    } 
    
    public function users()
    {
        return $this->belongsToMany(User::class,'pdf_reportes_users','pdf_reporte_id')
        ->withPivot('id','revocado','fecha_firma','pdf_reporte_id','tipo_firma_id','conciliacion_id','tipo_usuario_id','user_id','token','codigo','firmado')
        ->withTimestamps();
    } 

    public function temporales()
    {
        return $this->hasMany(ConciliacionPdfTemporal::class,'reporte_pdf_id','reporte_id');
    } 
   
}
