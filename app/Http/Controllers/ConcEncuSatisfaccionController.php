<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auditoria;
use App\ConcEncuestaSatisf;
use App\Expediente;
use App\Mail\RegConcEncuestaSatSuccess;
use App\Services\ConcEncuSatisfaccionService;
use Illuminate\Support\Facades\Mail;

class ConcEncuSatisfaccionController extends Controller
{

    private $encuestaService;
  
    public function __construct(ConcEncuSatisfaccionService $encuestaService)
    {
      $this->encuestaService = $encuestaService;
     
  }

    public function index(){

    	$auditorias = Auditoria::orderBy('created_at','desc')->paginate(200);
    	return view('myforms.frm_auditoria',compact('auditorias'));
    }

    public function store(Request $request){
            $request['user_id'] = auth()->user()->id;
            $request['tipo_usuario_id'] = 1;
    		//$encuestsa = $this->encuestaService->store($request);
           // Mail::to(auth()->user()->email)->send(new RegConcEncuestaSatSuccess());
 
    		return response()->json($request->all());

    }
}
 