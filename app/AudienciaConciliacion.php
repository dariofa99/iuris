<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudienciaConciliacion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'conciliacion_audiencias';

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
						'id_conciliacion',
						'access_code',
						'fecha',
						'hora'
						];


    public function getFecha(){
        $fecha =   \Carbon\Carbon::parse($this->fecha); 
        $diaActual = $fecha->isoFormat('dddd D \d\e MMMM \d\e\l Y');
        $cadena = $diaActual." a las ".$this->hora;
        return $cadena; 
                       
    }


}
