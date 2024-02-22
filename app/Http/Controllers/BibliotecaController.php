<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Biblioteca;
use App\Services\BibliotecasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BibliotecaController extends Controller
{

  private $bibliotecaService;
  public function __construct(BibliotecasService $bibliotecaService)
  {
    $this->bibliotecaService = $bibliotecaService;
    Carbon::setlocale('es');
  }


  public function index(Request $request)
  {
    // array_map('unlink', glob(public_path('act_temp/'.currentUser()->id.'___*')));//elimina los archivos que el 
    //$bibliotecas = Biblioteca::where('bibliestado', 1)->orderBy('created_at', 'desc')->paginate(15);
    $bibliotecas = $this->bibliotecaService->index($request);
    return view('galeria.index', compact('bibliotecas'));
  }

  public function showBibliotecaOff(Request $request)
  {

   // $bibliotecas = Biblioteca::where('bibliestado', 0)->orderBy('created_at', 'DESC')->get();
    $request["bibliestado"] = 0;
    $bibliotecas = $this->bibliotecaService->index($request);
    $active_galeria = 'active';
    return view('galeria.show_biblioteca_inactiva', compact('bibliotecas', 'active_galeria'));
  }

  public function show()
  {
  }

  public function create()
  {
    $active_galeria = 'active';
    return view('galeria.create', compact('active_galeria'));
  }

  public function store(Request $request)
  {
    //dd('hola');

    if ($request->file('doc_file') != '') {
      $docum = $request->file('doc_file');
      $file_route = time() . "_" . $docum->getClientOriginalName();
      Storage::disk('files_bibliotecas')->put($file_route, file_get_contents($docum->getRealPath()));
      $biblidocnomgen = $file_route;
      $biblidocnompropio = $docum->getClientOriginalName();
      $biblidocruta = Storage::disk('files_bibliotecas')->url($file_route);
      $biblioteca = Biblioteca::create([
        'biblinombre' => $request['biblinombre'],
        'biblidescrip' => $request['biblidescrip'],
        'bibliid_ramaderecho' => $request['bibliid_ramaderecho'],
        'bibliid_tipoarchivo' => $request['bibliid_tipoarchivo'],
        'biblidocnompropio' => $biblidocnompropio,
        'biblidocnomgen' => $biblidocnomgen,
        'biblidocruta' => $biblidocruta,
        'biblidoctamano' => $docum->getSize(),
        'bibliusercreated' => currentUser()->idnumber,
        'bibliuserupdated' => currentUser()->idnumber,
      ]);
      if (session()->has('sede')) {
        $biblioteca->sedes()->attach(session('sede')->id_sede);
      }
      //$actdocruta = public_path($url);               

    }
    return response()->json($docum->getSize());
    //dd($request->file('doc_file')->getMimeType());
  }



  public function edit($id)
  {
    $biblioteca = $this->bibliotecaService->setRelations(['user', 'user_update', 'rama_derecho', 'categoria'])
      ->find($id);
    return response()->json($biblioteca);
  }

  public function update(Request $request)
  {

    $biblioteca = Biblioteca::find($request->biblioteca_id);
    $biblioteca->fill($request->all());
    $biblioteca->bibliuserupdated = Auth::user()->idnumber;
    if ($request->file('doc_file') != '') {
      if ($biblioteca->biblidocruta != '') {
        Storage::delete($biblioteca->biblidocruta);
        $docum = $request->file('doc_file');
        $file_route = time() . "_" . $docum->getClientOriginalName();
        Storage::disk('files_bibliotecas')->put($file_route, file_get_contents($docum->getRealPath()));
        $biblidocnomgen = $file_route;
        $biblidocnompropio = $docum->getClientOriginalName();
        $biblidocruta = Storage::disk('files_bibliotecas')->url($file_route);
        $biblioteca->biblidocnomgen = $file_route;
        $biblioteca->biblidocnompropio = $biblidocnompropio;
        $biblioteca->biblidocruta = $biblidocruta;
        $biblioteca->biblidoctamano = $docum->getSize();
      }
    }
    $biblioteca->save();        // 
    return response()->json($biblioteca);

    //dd($request);
  }

  public function change($id)
  {
    $biblioteca = Biblioteca::find($id);
    if ($biblioteca->bibliestado == 1) {
      $biblioteca->bibliestado = 0;
      $biblioteca->save();
      return redirect()->back();
    } elseif ($biblioteca->bibliestado == 0) {
      $biblioteca->bibliestado = 1;
      $biblioteca->save();
      return redirect()->back();
    }
  }
  public function bibliodowpdf($id)
  {
    array_map('unlink', glob(public_path('act_temp/' . currentUser()->id . '___*'))); //elimina los archivos que el 
    $biblioteca = Biblioteca::find($id);
    try {
      $url = 'app/files_bibliotecas/' . $biblioteca->biblidocnomgen;
      $rutaDeArchivo = storage_path($url);
      $filename = currentUser()->id . '___' . $biblioteca->biblidocnomgen;
      copy($rutaDeArchivo, public_path("act_temp/" . $filename));
      return redirect("act_temp/" . $filename);
    } catch (\ErrorException $e) {
      echo "Ups! Algo salio mal.<br>" . $e->getMessage();
    }
  }
}
