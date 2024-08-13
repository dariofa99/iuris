<?php
namespace App\Services;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface LoginService {

    public function login(Request $request);    

}