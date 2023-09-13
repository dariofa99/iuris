<?php

namespace App\Services;

use App\Periodo;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TurnosService {    
    public function index(Request $request);
  
} 
?>