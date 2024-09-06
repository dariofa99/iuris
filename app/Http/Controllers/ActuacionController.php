<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Actuacion;
use App\Conciliacion;
use App\ConciliacionEstado;
use App\ConciliacionPdfTemporal;
use App\Expediente;
use Carbon\Carbon;
use App\Segmento;
use App\Notifications\UserNotification;
use App\PdfReporte;
use App\PdfReporteDestino;
use App\Periodo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ActuacionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response 
   */


  public function actpdfdownload($id, $user_doc)
  {
    array_map('unlink', glob(public_path('act_temp/' . currentUser()->id . '___*'))); //elimina los archivos que el 
    try {
      $actuacion = Actuacion::find($id);
      if ($user_doc == 'docente') {
        $url = 'app/files_actuaciones/' . $actuacion->actdocnomgen_docente;
        $rutaDeArchivo = storage_path($url);
        $filename = currentUser()->id . '___' . $actuacion->actdocnompropio_docente;
        $filedes = $actuacion->actdocnompropio_docente;
      }
      if ($user_doc == 'estudiante') {
        $url = 'app/files_actuaciones/' . $actuacion->actdocnomgen;
        $rutaDeArchivo = storage_path($url);
        $filename = currentUser()->id . '___' . $actuacion->actdocnompropio;
        $filedes = $actuacion->actdocnompropio;
      }

      copy($rutaDeArchivo, public_path("act_temp/" . $filename));
    } catch (\Throwable $th) {
      //echo "Ups! Parece que el archivo ya no se encuentra en el servidor";;
      // return;
    }


    return redirect("act_temp/" . $filename);


    /*
                $actuacion= Actuacion::find($id);
                



                $url = 'app/files_actuaciones/'.$actuacion->actdocnomgen;
                $rutaDeArchivo = storage_path($url);

                $headers = array(
                  'Content-Type'=> 'application/pdf'
                );


                return response()->download($rutaDeArchivo, $actuacion->actdocnomgen, $headers);
*/
  }


  function vacations()
  {
  }


  public function index(Request $request)
  {
    $periodo = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
      ->where('sp.sede_id', session('sede')->id_sede)
      ->where('estado', true)
      ->first();
    $now = Carbon::now();
    $expediente = Expediente::where('expid', $request->id_control_list)->first();
    //
    $expediente->setNotActLimit();
    $actuaciones = $this->getActuacionesExp($request->id_control_list, 0);

    $vacaciones = DB::table("vacaciones_periodo")
      ->where("periodo_id", $periodo->id)->get();

    $vacaciones_text = DB::table("vacaciones_periodo")
      ->whereDate('fecha_fin', '>=', $now)
      ->where("periodo_id", $periodo->id)->first();

    return response()->json([
      "actuaciones" => $actuaciones,
      "vacaciones_text" => $vacaciones_text ? $vacaciones : false,
      "vacaciones" => count($vacaciones) > 0 ? $vacaciones : false
    ]);
  }

  public function get_act_ant(Request $request)
  {
    $now = Carbon::now();
    $periodo = Periodo::join('sede_periodos as sp', 'sp.periodo_id', '=', 'periodo.id')
      ->where('sp.sede_id', session('sede')->id_sede)
      ->where('estado', true)
      ->first();
    $vacaciones = DB::table("vacaciones_periodo")
      ->where("periodo_id", $periodo->id)->get();

    $vacaciones_text = DB::table("vacaciones_periodo")
      ->whereDate('fecha_fin', '>=', $now)
      ->where("periodo_id", $periodo->id)->first();

    $actuaciones = $this->getActuacionesExp($request->id_control_list, 1);

    return response()->json([
      "actuaciones" => $actuaciones,
      "vacaciones_text" => $vacaciones_text ? $vacaciones : false,
      "vacaciones" => count($vacaciones) > 0 ? $vacaciones : false
    ]);
    //return response()->json($actuaciones);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $expediente = Expediente::where('expid', '2019B-11')->first();
    $actuaciones = $expediente->setNotActLimit();

    //dd($actuaciones);
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
    $expediente = Expediente::where("expid", $request['actexpid'])->first();
    $validForCreate = 0;
    if (currentUser()->hasRole('estudiante')) {
      if ($request->input('actestado_id') == 136) {
        $errorMessage = 'No se puede crear el anexo, comprueba que no hayan anexos pendientes por revisar';
        $validForCreate = $expediente->verifyActuacionAnexoForCreate();
      } else if (!$request->has('parent_actuacion_id')) {
        $validForCreate = $expediente->verifyActuacionForCreate();
        $errorMessage = 'No se puede crear la actuación, comprueba que no hayan actuaciones pendientes por revisar';
      }
    }

    //return response()->json($validForCreate); 
    if ($request->actexpid and $validForCreate < 2) {

      if ($request->hasFile('actdocnomgen')) {
        $docum = $request->file('actdocnomgen');
        $nombre_arch = $docum->getClientOriginalName();
        $nombre_arch = htmlentities($nombre_arch);
        $nombre_arch = preg_replace('/\&(.)[^;]*;/', '\\1', $nombre_arch);
        $file_name = preg_replace('([^A-Za-z0-9. ])', '', $nombre_arch);
        $actdocnompropio = $file_name;
        $extencion = $request->file('actdocnomgen')->extension();
        $file_name = md5($file_name) . '.' . $extencion;
        $file_route = time() . "_" . $file_name;
        Storage::disk('files_actuaciones')->put($file_route, file_get_contents($docum->getRealPath()));
        $actdocnomgen = $file_route;
        $actdocruta = Storage::disk('files_actuaciones')->url($file_route);
                            
      } else {
        if (currentUser()->hasRole('estudiante')) {

          $docum = $request->file('actdocnomgen');
          $nombre_arch = $docum->getClientOriginalName();
          $nombre_arch = htmlentities($nombre_arch);


          return response()->json([
            'errors' => ['Se debe subir un archivo', $request->hasFile('actdocnomgen')]
          ]);
        }
        $actdocnomgen = '';
        $actdocnompropio = '';
        $actdocruta = '';
      }

      $actuacion = new Actuacion();
      $actuacion->actexpid = $expediente->expid;
      $actuacion->actnombre = $request['actnombre'];
      $actuacion->actdescrip = $request['actdescrip'];
      $actuacion->actfecha = $request['actfecha'];
      $actuacion->fecha_limit = $request['fecha_limit'];
      $actuacion->actestado_id = $request['actestado_id'];
      $actuacion->actdocnomgen = $actdocnomgen;
      $actuacion->actcategoria_id = 222;
      $actuacion->actdocnompropio = $actdocnompropio;
      $actuacion->actidnumberest = $expediente->expidnumberest;
      $actuacion->actdocruta = $actdocruta;
      $actuacion->actusercreated = currentUser()->idnumber;
      $actuacion->actuserupdated = currentUser()->idnumber;
      $actuacion->save();

      if ($request->has('parent_actuacion_id')) {
        $actuacion->revisionesExp()->attach($actuacion->id, [
          'rev_actexpid' => $request['actexpid'],
          'parent_rev_actid' => $request->parent_actuacion_id,
          //'rev_actid'=>$actuacion->id,
        ]);
      } else {
        $actuacion->revisionesExp()->attach($actuacion->id, [
          'rev_actexpid' => $request['actexpid'],
          'parent_rev_actid' => $actuacion->id,
          //'rev_actid'=>$actuacion->id,
        ]);
      }

      if ($actuacion->actestado_id == 140) {
        $user = $expediente->estudiante;
        $user->notification = 'Nueva notificación de caso';
        $user->link_to = '/expedientes/' . $expediente->expid . '/edit';
        $user->mensaje = 'Se ha asignado una nueva actuación. Exp: ' . $expediente->expid;
        $user->notify(new UserNotification($user));
      }

      if ($actuacion->actestado_id == 136) {
        $user = $expediente->getDocenteAsig();
        $user->notification = 'Nueva notificación de caso';
        $user->link_to = '/expedientes/' . $expediente->expid . '/edit';
        $user->mensaje = 'Se ha creado un anexo. Exp: ' . $expediente->expid;
        $user->notify(new UserNotification($user));
      }

      //  $actuaciones = $this->getActuacionesExp($request['actexpid'],0);



      return response()->json($request->all());
    }
    return response()->json([
      'errors' => [$errorMessage]
    ]);
  }

  private function getActuacionesExp($expediente_id, $bandera)
  {

    $user_id = Auth::user()->id;
    $userSession = DB::table('users as u')
      ->join('role_user as ru', 'ru.user_id', '=', 'u.id')
      ->join('roles as r', 'r.id', '=', 'ru.role_id')
      ->where('u.id', '=', $user_id)
      ->select('r.name', 'u.idnumber')
      ->first();
    $userSession->volver_correcciones = Auth::user()->can('volver_correcciones_actuacion');
    $expediente = Expediente::where('expid', $expediente_id)->first();
    // dd($expediente->getDocenteAsig()->idnumber);
    $expediente->getDocenteAsig()->idnumber == Auth::user()->idnumber ? $can_edit = true : $can_edit = false;
    //$userSession->role;
    $actuaciones = DB::table('actuacions as a')
      ->join('revisiones_actuacion as rv', 'rv.rev_actid', '=', 'a.id')
      ->join('referencias_tablas as r', 'r.id', '=', 'a.actestado_id')
      ->join('referencias_tablas as t3', 't3.id', '=', 'a.actcategoria_id')
      ->where(function ($query) use ($expediente, $bandera) {
        $bandera == 0 ? $query->where('a.actidnumberest', $expediente->expidnumberest)
          : $query->where('a.actidnumberest', '<>', $expediente->expidnumberest);
      })
      ->where('rv.rev_actexpid', '=', $expediente_id)
      //->where('a.actidnumberest',$expediente->expidnumberest)
      ->select('rv.parent_rev_actid', 'r.ref_nombre', 't3.ref_nombre as categoria',)
      ->groupBy('rv.parent_rev_actid')
      ->get();

    foreach ($actuaciones as $key => $act) {
      $actuaciones2 = DB::table('actuacions as a')
        ->join('revisiones_actuacion as rv', 'rv.rev_actid', '=', 'a.id')
        ->join('referencias_tablas as r', 'r.id', '=', 'a.actestado_id')
        ->where('rv.parent_rev_actid', '=', $act->parent_rev_actid)
        ->where('rv.rev_actid', '<>', $act->parent_rev_actid)
        ->where(function ($query) use ($expediente, $bandera) {
          $bandera == 0 ? $query->where('a.actidnumberest', $expediente->expidnumberest)
            : $query->where('a.actidnumberest', '<>', $expediente->expidnumberest);
        })
        ->select(
          'rv.parent_rev_actid',
          'rv.rev_actid',
          'a.id',
          'a.actnombre',
          'a.actfecha',
          'a.fecha_limit',
          'a.actcategoria_id',
          'a.actdocenrecomendac',
          'a.actestado_id',
          'r.ref_nombre',
          'a.actdocnomgen',
          'a.actdocenfechamod',
          'a.actdocnompropio',
          'a.actdocruta',
          'a.actusercreated',
          'a.actuserupdated',
          'a.actdescrip'
        )
        ->orderBy('a.created_at', 'desc')
        ->get();
      $parent = Actuacion::find($act->parent_rev_actid);
      $parent->conciliaciones;
      $act->parent = $parent;
      $act->user =  $userSession;
      $act->children = $actuaciones2;
      $act->docenteasig = $can_edit;
      $parent->files = $parent->files()->orderBy('files.created_at', 'desc')->get();
    }

    return $actuaciones;
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $userSession = Auth::user()->id_number;
    $actuacion = Actuacion::find($id); 

    $parentId = DB::table('revisiones_actuacion')
      ->select('parent_rev_actid', 'rev_actid')
      ->where('rev_actid', $id)->first();
    $actuacion->parent = $parentId;
    $actuacion->notas_f = $actuacion->getNotas();
    $actuacion->docente_update;
    $actuacion->user_created;
    $actuacion->estudiante;

    return response()->json(
      $actuacion->toArray()
    );
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
    // return response()->json($request->all()); 

    $actuacion = Actuacion::find($id);
    $actuacion->fill($request->all());
    $actuacion->actuserupdated = currentUser()->idnumber;


    if ($request->file('actdocnomgen') != '') {
      if ($actuacion->actdocruta != '') {
        Storage::delete($actuacion->actdocruta);
      }
      $docum = $request->file('actdocnomgen');
      $nombre_arch = $docum->getClientOriginalName();
      $nombre_arch = htmlentities($nombre_arch);
      $nombre_arch = preg_replace('/\&(.)[^;]*;/', '\\1', $nombre_arch);
      $file_name = preg_replace('([^A-Za-z0-9. ])', '', $nombre_arch);
      $actdocnompropio = $file_name;
      $file_name = md5($file_name) . '.' . $request->file('actdocnomgen')->extension();
      $file_route = time() . "_" . $file_name;
      //$file_route= time()."_".$docum->getClientOriginalName();
      Storage::disk('files_actuaciones')
        ->put($file_route, file_get_contents($docum->getRealPath()));
      $actdocnomgen = $file_route;
      $actdocruta = Storage::disk('files_actuaciones')->url($file_route);

      $actuacion->actdocnomgen = $actdocnomgen;
      $actuacion->actdocnompropio = $actdocnompropio;
      $actuacion->actdocruta = $actdocruta;
    }
    $actuacion->save();

    if ($actuacion->actcategoria_id == 223 and count($actuacion->conciliaciones) > 0) {
    }

    /*  if (currentUser()->hasRole("docente")){
                        //$actuacion= Actuacion::find($id);
                        $actuacion-> fill(['actdocenrevisa'=>currentUser()->idnumber]);
                        $actuacion-> save();
            } */

    return response()->json($request->all());
    return response()->json(
      $actuacion->toArray()
    );
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {


    $del = '';

    $buscaParent = DB::table('revisiones_actuacion')->where('parent_rev_actid', $id)->get();
    if (count($buscaParent) > 0) {
      foreach ($buscaParent as $key => $parentId) {
        $actuacion = Actuacion::find($parentId->rev_actid);
        if ($actuacion->actdocruta != '') {
          Storage::delete($actuacion->actdocruta);
        }

        if ($actuacion->actdocruta_docente != '') {
          Storage::delete($actuacion->actdocruta_docente);
        }
        $actuacion->delete();
      }
    } else {
      $actuacion = Actuacion::find($id);
      if ($actuacion->actdocruta != '') {
        Storage::delete($actuacion->actdocruta);
      }
      if ($actuacion->actdocruta_docente != '') {
        Storage::delete($actuacion->actdocruta_docente);
      }
      $actuacion->delete();
    }

    return response()->json(($actuacion));
  }

  public function updoc(Request $request, $id)
  {

    //dd($id);
    $actuacion = Actuacion::find($id);
    if ($request->file('actdocnomgen') != '') {
      $actuacion = Actuacion::find($id);
      if ($actuacion->actdocruta != '') {
        Storage::delete($actuacion->actdocruta);
      }

      $docum = $request->file('actdocnomgen');
      $nombre_arch = $docum->getClientOriginalName();
      $nombre_arch = htmlentities($nombre_arch);
      $nombre_arch = preg_replace('/\&(.)[^;]*;/', '\\1', $nombre_arch);
      $file_name = preg_replace('([^A-Za-z0-9. ])', '', $nombre_arch);
      $actdocnompropio = $file_name;

      $file_name = md5($file_name) . '.' . $request->file('actdocnomgen')->extension();
      $file_route = time() . "_" . $file_name;
      $file_route = time() . "_" . $docum->getClientOriginalName();
      Storage::disk('files_actuaciones')->put($file_route, file_get_contents($docum->getRealPath()));
      $actdocnomgen = $file_route;

      $actdocruta = Storage::disk('files_actuaciones')->url($file_route);


      $actuacion->actdocnomgen = $actdocnomgen;
      $actuacion->actdocnompropio = $actdocnompropio;
      $actuacion->actdocruta = $actdocruta;
      $actuacion->save();
    }

    return response()->json($actuacion);
  }


  public function storeRevision(Request $request)
  {


    $actuacion =  Actuacion::find($request['idact']);
    //  return response()->json($actuacion->id);

    if ($request->file('actdocnomgen') != '') {
      if ($actuacion->actdocruta_docente != '') {
        Storage::delete($actuacion->actdocruta_docente);
      }

      $docum = $request->file('actdocnomgen');
      $nombre_arch = $docum->getClientOriginalName();
      $nombre_arch = htmlentities($nombre_arch);
      $nombre_arch = preg_replace('/\&(.)[^;]*;/', '\\1', $nombre_arch);
      $file_name = preg_replace('([^A-Za-z0-9. ])', '', $nombre_arch);
      $actdocnompropio = $file_name;

      $file_name = md5($file_name) . '.' . $request->file('actdocnomgen')->extension();
      $file_route = time() . "_" . $file_name;
      //$file_route= time()."_".$docum->getClientOriginalName();
      Storage::disk('files_actuaciones')->put($file_route, file_get_contents($docum->getRealPath()));
      $actdocnomgen = $file_route;
      $actdocruta = Storage::disk('files_actuaciones')->url($file_route);
    } else {

      $actdocnomgen = '';
      $actdocnompropio = '';
      $actdocruta = '';
    }
    $actuacion->actestado_id = $request['actestado_id'];
    $actuacion->actdocenrecomendac = $request['actdocenrecomendac'];
    $actuacion->actdocnomgen_docente = $actdocnomgen;
    $actuacion->actdocnompropio_docente = $actdocnompropio;
    $actuacion->actdocruta_docente = $actdocruta;
    if ($request['fecha_limit_doc'] != '') $actuacion->fecha_limit = $request['fecha_limit_doc'];
    $actuacion->actuserupdated = currentUser()->idnumber;

    $expediente  = Expediente::where('expid', $request->actexpid)->first();


    if ($request->ntaaplicacion) {

      //$expediente->estudiante;
      $docente_id = Auth::user()->idnumber;
      $estudiante_id = $expediente->estudiante->idnumber;
      $data = [
        'ntaaplicacion' => $request->ntaaplicacion,
        'ntaconocimiento' => $request->ntaconocimiento,
        'ntaetica' => $request->ntaetica,
        'ntaconcepto' => $request->ntaconcepto,
        'orgntsid' => $request->orgntsid,
        'segid' => $request->segid,
        'perid' => $request->perid,
        'tpntid' => $request->tpntid,
        'expidnumber' => $request->actexpid,
        'estidnumber' => $estudiante_id,
        'docidnumber' => $docente_id,
        'tbl_org_id' => $request['idact'],
      ];
      $actuacion->asignarNotas($data);

      //$actuacion->notas = json_encode($data);
    }

    if ($actuacion->actcategoria_id == 223 and count($actuacion->conciliaciones) > 0) {

      if ($request['actestado_id'] == 102) $estado_id = 176;
      if ($request['actestado_id'] == 104) $estado_id = 177;

      if ($request['actestado_id'] == 102 || $request['actestado_id'] == 104) {
        $conciliacion = Conciliacion::find($actuacion->conciliaciones[0]->id);
        $actuacion->actestado_id = $estado_id;
        if ($estado_id != $conciliacion->estado_id) {
          $estado = ConciliacionEstado::create([
            'concepto' => "Creado por docente",
            'type_status_id' => $estado_id,
            'user_id' => currentUser()->id,
            'conciliacion_id' => $conciliacion->id
          ]);
          $conciliacion->estado_id = $estado->type_status_id;
          $conciliacion->save();


          $reportes = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
            $query->where('is_copy', 1);
          })->whereHas('temporales', function (Builder $query) use ($conciliacion) {
            $query->where("conciliacion_id", $conciliacion->id);
          })->where(([
            "status_id" => $estado->type_status_id,
            "tabla_destino" => "conciliaciones"
          ]))->get();

          if (count($reportes) <= 0) {
            $data = PdfReporteDestino::whereHas('reporte', function (Builder $query) {
              $query->where('is_copy', 0);
            })
              ->where([
                "status_id" => $estado->type_status_id,
                "tabla_destino" => "conciliaciones"
              ])->get();


            $data->each(function ($data) use ($estado, $conciliacion) {
              //  $reporte_or = PdfReporte::find($reporte->id);
              $copy_reporte = PdfReporte::create(
                [
                  'reporte' => $data->reporte->reporte,
                  'report_keys' => $data->reporte->report_keys,
                  'nombre_reporte' => $data->reporte->nombre_reporte,
                  'configuraciones' => $data->reporte->configuraciones,
                  'is_copy' => 1
                ]
              );
              $reporDest = PdfReporteDestino::create([
                "status_id" => $estado->type_status_id,
                "tabla_destino" => "conciliaciones",
                "reporte_id" => $copy_reporte->id
              ]);
              $co_pdf = ConciliacionPdfTemporal::create([
                'reporte_pdf_id' => $copy_reporte->id,
                'status_id' => $estado->type_status_id,
                'parent_reporte_pdf_id' => $data->reporte->id,
                'conciliacion_id' => $conciliacion->id
              ]);

              $file_en = $data->reporte->files()->where('seccion', 'encabezado')->first();
              if ($file_en) {
                $data->reporte->files()->attach($file_en, [
                  'seccion' => 'encabezado',
                  'configuracion' => $file_en->pivot->configuracion
                ]);
              }

              $file_pie = $data->reporte->files()->where('seccion', 'pie')->first();
              if ($file_pie) {
                $data->reporte->files()->attach($file_pie, [
                  'seccion' => 'pie',
                  'configuracion' => $file_pie->pivot->configuracion
                ]);
              }
            });
          }
          $actuacion->actestado_id = $estado->type_status_id;
        }
      }
    }
    $actuacion->actdocenfechamod = date("Y-m-d H:i:s");
    $actuacion->save();
    if ($actuacion->actestado_id == 102 || $actuacion->actestado_id == 176) {
      $user = $expediente->estudiante;
      $user->notification = 'Nueva notificación de caso';
      $user->link_to = '/expedientes/' . $expediente->expid . '/edit';
      $user->mensaje = 'Se ha solicitado una correción a una actuación. Exp: ' . $expediente->expid;
      $user->notify(new UserNotification($user));
    }
    return response()->json($actuacion->toArray());
  }


  public function revisiones(Request $request, $id)
  {

    //return response()->json([$request->all()]);
    $actuacion = Actuacion::find($id);
    if ($request->has("new_estado")) {
      $actuacion->actestado_id = $request->get("new_estado");
    } else {
      switch ($actuacion->actestado_id) {
        case 136:
          $actuacion->actestado_id = 138;
          break;
        case 139:
          $actuacion->actestado_id = 102;
          $today = Carbon::now();
          $new_limit = $today->addDays(10);
          $actuacion->fecha_limit =  $new_limit;
          $notas = DB::table('notas')->where('tbl_org_id', $actuacion->id)->delete();
          break;
        case 138:
          $actuacion->actestado_id = 136;
          break;
        case 137:
          $actuacion->actestado_id = 139;
          break;
        case 139:
          $actuacion->actestado_id = 137;
          break;
      }
      $actuacion->actuserupdated = currentUser()->idnumber;
    }

    if ($request->has('actdocenrecomendac')) {
      $actuacion->actdocenrecomendac = $request->actdocenrecomendac;
      $actuacion->actdocenfechamod = date("Y-m-d");
    }
    $actuacion->save();

    return response()->json($actuacion->actestado_id);
  }
}
