<?php

namespace App\Services;

use App\AsigDocenteCaso;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface AsignacionDocenteCasosService {
    public function store(Request $request):AsigDocenteCaso;
    public function update(AsigDocenteCaso $expediente,Request $request):AsigDocenteCaso;
    public function find(int $id);
    public function findWithFilter(Array $filter):?Model;
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
 
    public function updateProfilePicture(User $user,Request $request):User;
     */
   
}
?>