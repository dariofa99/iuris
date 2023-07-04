<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface UsersService {
    public function find(int $id);
    public function getAllUsers(Request $request):LengthAwarePaginator;
    public function store(Request $request):User;
    public function getUsersByRoleName(String $role):Array;
    public function getDocentes():Array;
    public function getEstudiantes():Array;
    public function getDocentesByRama($rama):Array;
    public function getUsersByPermissionName($permission):Collection; 
    public function findUserByNameOrLastNameAndRole(String $name,$role,$verify_status=false):Array; 
    public function findWithFilter(Array $filter):?Model;
    public function getWithFilter(Array $filter):?Collection;
    public function setValidateSede($status);
    public function addSede(User $user);
    public function changeSede(User $user);
    public function update(User $user,Request $request):User;
    public function updateProfilePicture(User $user,Request $request):User;
    
   
}
?>