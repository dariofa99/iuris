<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biblioteca extends Model
{
   /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bibliotecas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

            'id',
            'biblinombre',
            'biblidescrip',
            'bibliid_ramaderecho',
            'bibliid_tipoarchivo',
            'biblidocnompropio',
            'biblidocnomgen',
            'biblidocruta',
            'bibliestado',
            'biblidoctamano',
            'bibliusercreated',
            'bibliuserupdated'
             			   ];


    
    public function sedes(){
        return $this->belongsToMany(Sede::class,'sede_bibliotecas','biblioteca_id','sede_id')
                                ->withPivot('id','sede_id','biblioteca_id')
                                ->withTimestamps(); 
    } 
    public function scopeCriterio($query,$data){

        
       // dd($data->bibliid_ramaderecho);
         
            if ($data->bibliid_ramaderecho !='' and $data->bibliid_tipoarchivo !='') {
               return $query->where('bibliid_ramaderecho',$data->bibliid_ramaderecho)->where('bibliid_tipoarchivo',$data->bibliid_tipoarchivo);
            }elseif($data->bibliid_ramaderecho !=''){
                return $query->where('bibliid_ramaderecho',$data->bibliid_ramaderecho);
            }elseif($data->bibliid_tipoarchivo !=''){
                return $query->where('bibliid_tipoarchivo',$data->bibliid_tipoarchivo);
            }

    }       

    public function isFile($type){
         
        $archivo = $this->biblidocnompropio;
       $datos_archivo = pathinfo($archivo); 
        $ext = $datos_archivo['extension']; //Cambio el nombre al archivo   
      //dd($archivo);
        switch ($type) { 
    //0->naranja, 1->Azul oscuro, 2->verde, 3->gris, 4->rojo

            case 'pdf':
                if ($ext == 'pdf') {
                   return true;
                }                 
                break;
            case 'xlsx':
                if ($ext == 'xlsx') {
                   return true;
                }
                break;
            case 'xls':
                if ($ext == 'xls') {
                   return true;
                }
                break;
            case 'doc':
                if ($ext == 'doc') {
                   return true;
                }
                break;
            case 'jpg':
                if ($ext == 'jpg') {
                   return true;
                }
                break;
            case 'jpeg':
                if ($ext == 'jpeg') {
                   return true;
                }
                break; 
            case 'png':
                if ($ext == 'png') {
                   return true;
                }
                break;        
            case 'docx':
                if ($ext == 'docx') {
                   return true;
                }
                break;                    
            default:
               return false;
               break;

    }

    return false;

    }

    public function categoria()    {
        return $this->belongsTo(TipoArchivo::class, 'bibliid_tipoarchivo', 'id');
    }
    public function rama_derecho()    {
        return $this->belongsTo(RamaDerecho::class, 'bibliid_ramaderecho', 'id');
    }

    public function user()    {
        return $this->belongsTo(User::class, 'bibliusercreated', 'idnumber');
    }
    public function user_update()    {
        return $this->belongsTo(User::class, 'bibliuserupdated', 'idnumber');
    }

}
