<?php

namespace App\Services;

use App\Biblioteca;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface BibliotecasService {

    
    public function index(Request $request);
    public function store(Request $request):Biblioteca;
    public function update(Biblioteca $periodo,Request $request):Biblioteca;
   
}
?>