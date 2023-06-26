<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ReferencesStaticData;
use App\Traits\UploadFile;
class ConciliacionReporte extends Model
{
    use UploadFile;

    public $disk = 'conciliacion_reporte_files';
    protected $table = 'conciliaciones_reportes';

    protected $fillable = [
        'reporte',
        'report_keys',
        'conciliacion_id',
        'status_id'
    
    ];

    public function usuarios()
    {
       return $this->belongsToMany(User::class,'conciliacion_has_user','conciliacion_id')
       ->withPivot('user_id','tipo_usuario_id','conciliacion_id')->withTimestamps();
    } 
    
    public function aditional_static_data()
    {
        return $this->hasMany(ConciliationAditionalStaticData::class, 'conciliacion_id', 'id');
    }
    public function estados()
    {
        return $this->hasMany(ConciliacionEstado::class, 'conciliacion_id', 'id');
    }

    public function comentarios()
    {
        return $this->hasMany(ConciliacionComentario::class, 'conciliacion_id', 'id');
    }

    public function estado()
    {
        return $this->belongsTo(TablaReferencia::class, 'estado_id', 'id');
    }

    public function categoria()
    {
        return $this->belongsTo(TablaReferencia::class, 'categoria_id', 'id');
    }

    
    public function files(){
        return $this->belongsToMany(File::class,'conciliacion_has_files','conciliacion_id')
        ->withPivot('id','concepto','file_id','type_status_id')->withTimestamps(); 
     }  

    public function getStaticDataVal($name,$section,$option_id=null){
        $ref_data = ReferencesStaticData::where(['name'=>$name,'section'=>$section])->first();
    //  dd( $ref_data);
        if ($ref_data) {           
            $data = $this->aditional_static_data()
            ->where([
                'reference_data_id'=>$ref_data->id,
                'reference_data_option_id'=>$option_id == null ? $ref_data->options[0]->id : $option_id
                    ])->first();
                    
                   // dd( $data);
            if($data){
                return $data;
            }
        }
        ;
       return false;
    }

    public function getStaticDataLabel($name,$section){
        $ref_data = ReferencesStaticData::where(['name'=>$name,'section'=>$section])->first();
      //dd( $ref_data->options[0]);
        if ($ref_data) {       
                return $ref_data;            
        }
       return false;
    }
}
