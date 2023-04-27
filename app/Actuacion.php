<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Expediente;
use App\Traits\AsigNotas;
use Carbon\Carbon;
class Actuacion extends Model
{
    use AsigNotas;
    /**
     * The database table used by the model.
     * 
     * @var string
     */
    public $origen = 2;
    protected $table = 'actuacions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
						    'actid',
                            'actexpid',
                            'actidnumberest',
						    'actnombre',
						    'actdescrip',
                            'actfecha',
                            'actdocenrevisa',
                            'actdocenrecomendac',
                            'actdocencpto',
                            'actdocenfechamod',
                            'actestado_id',
                            'actcategoria_id',
                            'notas',
                            'fecha_limit',
                            'actdocnomgen',
                            'actdocnompropio',
                            'actdocruta',
                            'actdocnomgen_docente',
                            'actdocnompropio_docente',
                            'actdocruta_docente',
                            'actusercreated',
                            'actuserupdated'
             		];

    public function __construct(){
        Carbon::setlocale('es');
    }

    public function expediente(){
       return $this->belongsTo(Expediente::class, 'actexpid','expid');
    }
    public function estudiante(){
        return $this->belongsTo(User::class, 'actidnumberest','idnumber');
     }

    public function revisionesExp(){
         return $this->belongsToMany(Expediente::class,'revisiones_actuacion','rev_actid','rev_actexpid')->withPivot('rev_actexpid','parent_rev_actid','rev_actid');
    }

    public function conciliaciones(){
        return $this->belongsToMany(Conciliacion::class,'conc_has_exp','actuacion_id','conciliacion_id')
        ->withPivot('actuacion_id','conciliacion_id');
   }

    public function docente_update(){
       return $this->belongsTo(User::class, 'actuserupdated','idnumber');
    }

    public function user_created(){
        return $this->belongsTo(User::class, 'actusercreated','idnumber');
     }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'tbl_org_id', 'id');
    }

    public function files(){
        return $this->belongsToMany(File::class,'actuaciones_has_files','actuacion_id')
        ->withPivot('id','file_id','actuacion_id')->withTimestamps(); 
     }  


} 
 