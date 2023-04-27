<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Redirect;
use Carbon\Carbon;
use App\Expediente;
use App\User;
use App\Actuacion;
use DB;



class ExpedienteCierreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //dd('entra');
       //dd($id);
          
          
          $expediente = Expediente::where("expid","=",$id) ->get(['expestado', 'expcierrecasocpto', 'expcierrecasonotaest', 'expcierrecasonotadocen'])->first();


         
           return response()->json(
                  $expediente->toArray()
              ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
             if($request->ajax()){

                               
                                          

                          $id = DB::table('expedientes')                            
                            ->select('id')
                            ->where('expid', '=', $id)
                            ->get();



                                 foreach ($id as $id)
                                     {
                                       $idexpediente=$id->id;
                                     }

                            //dd($idexpediente);


                            
                           $expediente=Expediente::find($idexpediente);
                           
                           $expediente->fill($request->all());
                            //dd($expediente);
                           $expediente->save();       

                
                          return response()->json(
                              $expediente->toArray()
                          ); 


        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
