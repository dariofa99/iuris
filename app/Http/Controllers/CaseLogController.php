<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Storage;
use DB;
use App\Periodo;
use App\TablaReferencia;
use App\User;
use App\CaseLog;
use Facades\App\Facades\NewPush;
use App\Notifications\UserNotification;
class CaseLogController extends Controller
{


    public function index(Request $request){


    	//	return  view('myforms.frm_turnos_students_list',['dias'=>$dias,'oficinas'=>$oficinas,'ref_horarios'=>$ref_horarios,'ref_color'=>$ref_color,'turnos'=>$turnos,'cursando'=>$cursando,'colores'=>$colores,'data_search'=>$data_search,'estudiantes'=>$estudiantes]);
	}

	public function store(Request $request){
        $request["user_id"] = auth()->user()->id;
        $request["type_status_id"] = 152;
        $caseL = CaseLog::create($request->all());
		if($request->file('log_file')!=''){
            $notification_message = 'documento';
            $file = $caseL->uploadFile($request->file('log_file'),'/exp_'.$caseL->exp_id);
            $caseL->files()->attach($file,['type_status_id'=>1]);                   
		}
		$expediente = $caseL->expediente;	
		$response = [];
		$response['doc_files'] = view('myforms.components_exp.frm_list_notificaciones',compact('expediente'))->render();
		$response['type_log_id'] = $caseL->type_log_id;
		$user = $expediente->solicitante;
		$user->notification = 'Nueva notificación de caso';
		$user->link_to = '/oficina/solicitante/solicitud/'.$expediente->solicitudes[0]->id;
		$user->mensaje = $caseL->description;
		$user->notify(new UserNotification($user));
		$notifications = view('layouts.front.notifications',compact('user'))->render();
		NewPush::channel('notifications_'.$user->idnumber)
		->message(['notifications'=>$notifications])->publish();
        return response()->json($response); 

		
	}


	public function edit($id){
		$caseL = CaseLog::find($id);
		$caseL->files;
		$response = [];
		$response['caseL'] = $caseL;
		return response()->json($response);
	}

	public function update(Request $request,$id){
		$caseL = CaseLog::find($id);
		$caseL->fill($request->all());
		$caseL->save();
		if($request->file('log_file')!=''){
		if ($caseL->files()->first() and $caseL->files()->first()->path!='') {
			Storage::delete($caseL->files()->first()->path);     
			$file = $caseL->files()->first()->delete();  
			$file = $caseL->uploadFile($request->file('log_file'),'/exp_'.$caseL->exp_id);
            $caseL->files()->attach($file,['type_status_id'=>1]);     
		}
		}
		$expediente = $caseL->expediente;
		$response = [];
		$response['caseL'] = $caseL;
		$response['type_log_id'] = $caseL->type_log_id;
		$response['doc_files'] = view('myforms.components_exp.frm_list_documents',compact('expediente'))->render();
		return response()->json($response);
	}

	public function destroy($id){
		
		$caseL = CaseLog::find($id);
		$expediente = $caseL->expediente;
		$caseL->delete();		
		$response = [];
		$response['caseL'] = $caseL;
		$response['type_log_id'] = $caseL->type_log_id;
		$response['doc_files'] = view('myforms.components_exp.frm_list_documents',compact('expediente'))->render();
		return response()->json($response);
	}

	public function downloadFileLog($lgid){
		array_map('unlink', glob(public_path('act_temp/'.auth()->user()->id.'___*')));//elimina los archivos que el 
		$logfile= File::find($lgid);
		if ($logfile) {
			$rutaDeArchivo = storage_path($logfile->path);
			$filename = auth()->user()->id.'___'.$logfile->original_name;			
			copy( $rutaDeArchivo, public_path("act_temp/".$filename));
			
			return redirect("act_temp/".$filename); 
		}
	   }

	
  

 

}
