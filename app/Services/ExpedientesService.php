<?php

namespace App\Services;

use App\AsignacionCaso;
use App\Expediente;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ExpedientesService {
    public function index(Request $request);
    public function getColorsAsesorias(Request $request);
    public function store(Request $request):Expediente;
    public function update(Expediente $expediente,Request $request):Expediente;
    public function find(int $id);
    public function findWithFilter(Array $filter):?Model;
    public function asignarDocente(AsignacionCaso $asignacionCaso);
    public function asignargDocenteSeguimiento(AsignacionCaso $asignacionCaso,$tipoproceso);
    public function getActuacions($expid,$only);
    public function pausarExpediente($expediente, Request $request);
    public function deletePausa($id);
    /* public function getAllUsers(Request $request):LengthAwarePaginator;
    
    public function getUsersByRoleName(String $role):Array;
    public function getDocentes():Array;
    public function getEstudiantes():Array;
    public function getDocentesByRama($rama):Array;
    public function getUsersByPermissionName($permission):Collection; 
    public function findUserByNameOrLastNameAndRole(String $name,$role):Array; 
    public function setValidateSede($status);
    public function addSede(User $user);
    public function changeSede(User $user);
 
      */
   
}
?>