<?php

namespace App\Http\Controllers;

use App\Conciliacion;
use Illuminate\Http\Request;
use App\User;
use App\Roles;
use App\Expediente;

use App\Solicitud;
use App\ReferenceDataOptions;
use App\ReferencesData;
use App\UserAditionalData;
use DB;
use Facades\App\Facades\NewPush;

class ExpedienteUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


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

        //return response()->json(session('sede')->id_sede);

        if($request->ajax()){

            if(!$request->has('password')) $request['password'] = $request->idnumber;
            if(!$request->has('email')) $request['email'] = $request->idnumber."@email.com";

            $user=User::create($request->all()); 
            $user->roles()->attach(8); 
            if($request->has("solicitud_id")){
                $solicitud = Solicitud::find($request->solicitud_id); 
                $solicitud->type_status_id = 165;
                $solicitud->save();                
                NewPush::channel('solicitudes_send')->message([
                  'solicitud_id'=>$solicitud->id,                            
                  ])->publish();    
            }
            if($request->has('reference_data_id')){   
                foreach ($request->reference_data_id as $key => $rd_id) {
                    $var = "value_".$rd_id;
                    $data = $request->$var;             
                    $var2 = "value_text_".$rd_id;
                    $data_text = $request->$var2; 
                    $value_other_text = 'value_other_text_'.$rd_id;
                    $data_value_other_text = $request->$value_other_text;        
                    $reference = ReferencesData::find($rd_id);        
                    if($data){                
                        if($reference->type_data_id==168){
                            $uad = UserAditionalData::create([
                                'reference_data_id'=>$rd_id,
                                'user_id'=>$user->id,
                                'reference_data_option_id'=>$data[0],
                                'value'=>$data_text[0],
                                'value_is_other'=>$data_value_other_text ? $data_value_other_text[0]:'',
                            ]);
                        }else{
                            foreach ($data as $key_2 => $option) {
                                $op_value = ReferenceDataOptions::find($option);
                                if($op_value){
                                    $uad = UserAditionalData::create([
                                        'reference_data_id'=>$rd_id,
                                        'user_id'=>$user->id,
                                        'reference_data_option_id'=>$option,
                                        'value'=>$op_value->value,
                                        'value_is_other'=>$data_value_other_text[0],
                                        ]);
                                }                                  
                            }
                        }     
                    }
                }   
            }  
            $response=[];        
            if(session()->has('sede')){
                $user->sedes()->attach(session('sede')->id_sede);
            }
              if($request->has('conciliacion_id')) {
               // $userc_con = count($user->conciliaciones()->where(['conciliacion_id'=>$request->conciliacion_id,'tipo_usuario_id'=>$request->tipo_usuario_id])->get());
                 //   if($userc_con <= 0 ){
                        $conciliacion = Conciliacion::find($request->conciliacion_id);
                        $conciliacion->usuarios()->attach($user->id,[
                            'tipo_usuario_id'=>$request->tipo_usuario_id,
                            'estado_id'=>1
                        ]);
                  //  }
                
           } 
           $response ['idnumber'] = $request->idnumber;
             return response()->json($response); 
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($idnumber)
    {          
        $user= User::where('idnumber', '=',$idnumber)->take(1)->first();    
        $adtda = view('myforms.components_user.aditional_comp_data',compact('user'))->render();
        $response = [
            'user'=> $user,
            'aditional_view'=> $adtda  
        ];
        return response()->json($response); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){     
        $user= User::find($id);
        $adtda = view('myforms.components_user.aditional_comp_data',compact('user'))->render();
        $response = [
             'user'=> $user,
             'aditional_view'=> $adtda  
         ];
        return response()->json($response); 
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
       // dd($request->all());
        $user= User::find($id);
        if($request->has('reference_data_id')){   
        foreach ($request->reference_data_id as $key => $rd_id) {
            $var = "value_".$rd_id;
            $data = $request->$var;             
            $var2 = "value_text_".$rd_id;
            $data_text = $request->$var2; 
            $value_other_text = 'value_other_text_'.$rd_id;
            $data_value_other_text = $request->$value_other_text;
            $reference = ReferencesData::find($rd_id); 
            $uad = UserAditionalData::where([
                'reference_data_id'=>$rd_id,
                'user_id'=>$id,             
                ])
            ->first(); 
            if($data){                
                if($uad){                   
                    if($reference->type_data_id==168){
                        $uad->value = $data_text[0];
                        $uad->save();
                    }elseif ($reference->type_data_id==169) {
                        //si es opcion multiple unica respuesta                
                        $op_value = ReferenceDataOptions::find($data[0]);
                        if($op_value){
                            $uad->value = $op_value->value;
                            $uad->value_is_other = $data_value_other_text != null ? $data_value_other_text[0] : '';
                            $uad->reference_data_option_id = $data[0];
                            $uad->save();
                        }else{
                            $uad_del = UserAditionalData::where([
                                'reference_data_id'=>$rd_id,
                                'user_id'=>$id,
                                //'reference_data_option_id'=>$option
                                ])
                            ->delete();
                        }
                       
                    }elseif ($reference->type_data_id==170) {
                        //si es opcion multiple varias respuestas
                        $uad_del = UserAditionalData::where([
                            'reference_data_id'=>$rd_id,
                            'user_id'=>$id,
                            //'reference_data_option_id'=>$option
                            ])
                        ->delete();
                        foreach ($data as $key_2 => $option) {
                            $op_value = ReferenceDataOptions::find($option);
                                $uad = UserAditionalData::create([
                                    'reference_data_id'=>$rd_id,
                                    'user_id'=>$id,
                                    'reference_data_option_id'=>$option,
                                    'value'=>$op_value->value,
                                    'value_is_other'=>$data_value_other_text != null ? $data_value_other_text[0] : '',
                                    ]);
                        }

                    }
                }else{
                    if($reference->type_data_id==168){
                        //dd($data_value_other_text);
                        $uad = UserAditionalData::create([
                            'reference_data_id'=>$rd_id,
                            'user_id'=>$id,
                            'reference_data_option_id'=>$data[0],
                            'value'=>$data_text != null ? $data_text[0] : '',
                            'value_is_other'=>$data_value_other_text != null ? $data_value_other_text[0] : '',
                        ]);
                    }else{
                        foreach ($data as $key_2 => $option) {
                            $op_value = ReferenceDataOptions::find($option);
                            if($op_value){
                                $uad = UserAditionalData::create([
                                    'reference_data_id'=>$rd_id,
                                    'user_id'=>$id,
                                    'reference_data_option_id'=>$option,
                                    'value'=>$op_value->value,
                                    'value_is_other'=>$data_value_other_text != null ? $data_value_other_text[0] : '',
                                    ]);
                            }
                              
                        }
                    }
                }        
            }else{
                if($uad){
                    $uad_del = UserAditionalData::where([
                        'reference_data_id'=>$rd_id,
                        'user_id'=>$id,
                        //'reference_data_option_id'=>$option
                        ])
                    ->delete();
                }
            }
        }   
    }  
    if(session()->has('sede')){
        $user->sedes()->sync(session('sede')->id_sede);
      }
        
        if($request->ajax()){  
            if($request->has('conciliacion_id') and $user->active == 1) $request['active'] = 1; 
            if($request->has('conciliacion_id') and $user->cursando_id != 1) $request['cursando_id'] = $user->cursando_id;   
            $user->fill($request->all());           
            $result=$user->save();             
            $response=[];
        if($request->has('expediente_id')){
            $expediente = Expediente::find($request->expediente_id);
            if($user->active and count($expediente->solicitudes)<=0){
                $solicitud = new Solicitud();
                $solicitud->turno = 0;
                $solicitud->idnumber = $user->idnumber;
                $solicitud->name = $user->name;
                $solicitud->tel1 = $user->tel1;
                $solicitud->lastname = $user->lastname;
                $solicitud->estrato_id = $user->estrato_id;
                $solicitud->tipodoc_id = $user->tipodoc_id;
                $solicitud->type_category_id = 166; 
                $solicitud->type_status_id = 162;                
                $solicitud->number = time();
                $solicitud->save();
                if(session()->has('sede')){
                $solicitud->sedes()->attach(session('sede')->id_sede);  
                }
                $expediente->solicitudes()->attach($solicitud->id);              
                $expediente = Expediente::find($request->expediente_id);
                $response['view'] = view("myforms.components_exp.frm_oficina_virtual",compact('expediente'))->render();
            }
        }
      //dd($request->all());
        if($request->has('conciliacion_id')) {
            $userc_con = count($user->conciliaciones()->where([
                'conciliacion_id'=>$request->conciliacion_id,
                'tipo_usuario_id'=>$request->tipo_usuario_id])
                ->get());
                $conciliacion = Conciliacion::find($request->conciliacion_id);
                if($userc_con <= 0 ){                   
                    $conciliacion->usuarios()->attach($user->id,[
                        'tipo_usuario_id'=>$request->tipo_usuario_id,
                        'estado_id'=>1
                    ]);
                }
            
       }     

            if($result){
                $response['user'] = $user;
              return response()->json($response); 

            }else{

                return response()->json(['success'=>'false'] ); 

            }
 

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
