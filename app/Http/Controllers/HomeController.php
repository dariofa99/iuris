<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd("");
        return view('home');
    }

    public function search(Request $request)
    {
       
        if($request->has('type') and $request->type = "estudiante")
        return view('myforms.frm_result_consultas_home');
    }
}
