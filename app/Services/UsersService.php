<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Collection;

interface UsersService {

    public function store(Request $request):User;
    public function getUsersByRoleName(String $role):Array;
    public function getDocentes():Array;
    public function getEstudiantes():Array;
    public function getDocentesByRama($rama):Array;
    public function getUsersByPermissionName($permission):Collection; 

}
?>