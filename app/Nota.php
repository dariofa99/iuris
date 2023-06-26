<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
							'nota',
							'estidnumber',
							'docidnumber',
							'expidnumber',
							'cptnotaid',
							'segid',
							'orgntsid',
							'tpntid',
                            'perid',
							//'notusercreated',
							'tbl_org_id',
							'created_at',
							'updated_at'

                    	   ];

      public function segmento()
    {
        return $this->belongsTo(Segmento::class, 'segid', 'id');
    }
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'perid', 'id');
    }

     public function concepto()
    {
        return $this->belongsTo(Cptonota::class, 'cptnotaid', 'id');
    }

    public function tipo_nota()
    {
        return $this->belongsTo(TipoNota::class, 'tpntid', 'id');
    }

    public function origen()
    {
        return $this->belongsTo(OrigenNota::class, 'orgntsid', 'id');
    }
    
        public function docente_eva()
    {
        return $this->belongsTo(User::class, 'docidnumber', 'idnumber');
    }
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'docidnumber', 'idnumber');
    }

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'expidnumber', 'expid');
    }


    function getNotaFinal($notas){

        $totalNotas = 0;
        $final = 0;
        foreach ($notas as $key => $nota) {
          if (is_numeric($nota->nota)) {
             $final += $nota->nota;
             $totalNotas++;
          }           
        }
        $final = number_format(($final/$totalNotas),2,'.','.');
        return $final;
      

    }                     

}
