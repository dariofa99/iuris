<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\Cursos;

class Asigna_docen_est extends Model
{

	use Cursos;

	protected $table = 'asigna_docent_ests';
	protected $fillable = [
		'asgedidnumberest',
		'asgedidnumberdocen',
		'asgedidperiodo',
		'confirmado',
		'asgedusercreated',  
		'asgeduserupdated'
		];


	public function estudiante(){
		return $this->belongsTo(User::class, 'asgedidnumberest', 'idnumber');
	}

	public function docente(){
		return $this->belongsTo(User::class, 'asgedidnumberdocen', 'idnumber'); 
	}

	public function periodo(){
		return $this->belongsTo(Periodo::class, 'asgedidperiodo', 'id');
	}

 



}
 