<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AsigNotas;

class Requerimiento extends Model
{
    use AsigNotas;
       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requerimientos';
    public $origen = 3;
    public $userSession;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
'userSession',
    					    'reqid',       
				            'reqexpid',
				            'reqidsolicitan',
				            'reqidest',
				            'reqidmotivo',
				            'reqmotivo',
				            'reqdescrip',
				            'reqfecha',
                            'reqhora',
                            'reqid_asistencia',
                            'reqcomentario_est',
                            'reqcomentario_coorprac',
                            'reqentregado',
				            'evaluado',
                            'notas',
				            //'reqestado',
				            'requsercreated',
				            'requserupdated'

             			   ];
    public function expediente(){
    return $this->belongsTo(Expediente::class, 'reqexpid','expid');
   }  

   public function req_asistencia(){
    return $this->belongsTo(ReqAsistencia::class, 'reqid_asistencia','reqid_refasis');
   } 

   function getFechaCorta($date){
        $fecha = substr($date,0,11);
        return $fecha;
       // dd($fecha);
   }

   public function scopeCriterio($query ,$data,$criterio)
    {
           if (trim($data) != '')
        {

            switch ($criterio) { 
                case 'codido_exp':
                   return $query->where('reqexpid', $data); 
                    break;
                case 'estudiante':
                case 'estudiante_num':
                    return $query->where('expidnumberest', $data); 
                    break;
                case 'consultante':
                case 'consultante_num':
                    return $query->where('expidnumber', $data); 
                    break;
                case 'estado':
                    return $query->where('expestado_id', $data); 
                    break;
                case 'tipo_consulta':
                    return $query->where('exptipoproce_id', $data); 
                    break; 
                case 'fecha_creacion':
                    return $query->where('created_at', $data); 
                    break; 
                case 'fecha_cita':
                    return $query->where('reqfecha', $data); 
                    break;                
            }           
                     
        }

    }

}














