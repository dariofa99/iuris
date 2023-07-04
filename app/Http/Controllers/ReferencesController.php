<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EstadosService;

class ReferencesController extends Controller
{
    private $estadosService; 

    public function __construct(EstadosService $estadosService)
    {
      $this->estadosService = $estadosService;
    }

    public function getEstadosForExpediente(){

        return response()->json(["ja"]);
    }
}