<?php

namespace App\Http\Controllers;



class ReferencesController extends Controller
{
     

    public function __construct()
    {
     
    }

    public function getEstadosForExpediente(){

        return response()->json(["ja"]);
    }
}