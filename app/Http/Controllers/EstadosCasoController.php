<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Expediente;
use App\User;
use DB;
use App\EstadoCaso;
use App\Periodo;
use Session;
use Carbon\Carbon;
use App\Segmento;
use App\Services\AsignacionDocenteCasosService;
use App\Services\EstadosCasoService;
use App\Services\ExpedientesService;
use App\Services\SegmentosService;
use Illuminate\Support\Facades\Auth;

class EstadosCasoController extends Controller
{

    private $estadoCasoService;
    private $segmentosService;
    private $expedienteService;

    public function __construct(
        ExpedientesService $expedienteService,
        EstadosCasoService $estadoCasoService,
        SegmentosService $segmentosService
    ) {
        $this->estadoCasoService = $estadoCasoService;
        $this->segmentosService = $segmentosService;
        $this->expedienteService = $expedienteService;
    }

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

        if ($request->header('X-Requested-With') == 'XMLHttpRequest') {

            $expediente = $this->expedienteService->findWithFilter([
                'expid' => $request->expid
            ]);
            $estudiante_id = $expediente->estudiante->idnumber;
            $date = Carbon::now()->format('Y-m-d');
            $acts =  $expediente->verifyNotAct($date);
            $reqs =  $expediente->verifyNotReq($date);
            //return response()->json($acts); 
            $request['expidnumber'] = $request->expid;
            $request['ref_estado_id'] = $request->new_expestado;
            $request['ref_motivo_estado_id'] = $request->motivo_estado;
            $expediente->expestado_id = $request->new_expestado;
            $role = '';
            if (!currentUser()->hasRole('estudiante')) $role = 'docente';
            if ($request->new_expestado == 2) {
                if ((count($acts) <= 0 and count($reqs) <= 0) || $expediente->exptipoproce_id == 1) {
                    if (count($expediente->notas) > 0) {
                        $nota = $expediente->get_nota_corte('conocimiento');
                        if (count($nota) > 0 and ($nota['tipo_id']) == 0 and count($expediente->get_has_nota_final()) <= 0) {
                            $response = [
                                'mensaje' => 'El Caso NO tiene notas asignadas',
                                'guardado' => false,
                                'exp' => $expediente,
                                'role' => $role, 
                            ];
                            return response()->json(($response));
                        }
                        if ($nota['tipo_id'] == 1 || count($expediente->get_has_nota_final()) > 0) {


                            $estadoCaso = $this->estadoCasoService->store($request);
                            $expediente->save();
                            $response = [
                                'mensaje' => 'El Caso fue actualizo con éxito',
                                'guardado' => true,
                                'exp' => $expediente,
                                'role' => $role,
                            ];
                        } else {
                            $response = [
                                'mensaje' => 'NO se puede cerrar el caso porque tiene asignadas las notas como parciales, si desea cerrarlo debe cambiar las notas asignadas como DEFINITIVAS',
                                'guardado' => false,
                                'exp' => $expediente,
                                'role' => $role,
                            ];
                        }
                    } else {
                        $response = [
                            'mensaje' => 'El Caso no tiene notas asignadas',
                            'guardado' => false,
                            'exp' => $expediente,
                        ];
                    }
                } else {
                    $mensaje = '';
                    if (count($reqs) > 0) $mensaje .= 'Hay ' . count($reqs) . ' requerimientos que requieren ser entregados <br>';
                    if (count($acts) > 0) $mensaje .= 'Hay ' . count($acts) . ' actuaciones que requieren ser revisadas';

                    $response = [
                        'mensaje' => $mensaje,
                        'guardado' => false,
                        'exp' => $expediente,
                    ];
                }
            } else {

                if (currentUser()->hasRole('estudiante')) {
                    if ($expediente->exptipoproce_id != 3 and (empty($expediente->exphechos) || empty($expediente->exprtaest))) {
                        $response = [
                            'mensaje' => 'El Caso NO tiene información en hechos o en respuesta del estudiante',
                            'guardado' => false,
                            'exp' => $expediente,
                            'role' => $role,
                        ];
                        return response()->json($response);
                    } elseif ($expediente->exptipoproce_id == 3 and empty($expediente->exphechos)) {
                        $response = [
                            'guardado' => false,
                            'mensaje' => 'No se puede cerrar el caso porque no se han descrito los hechos del caso',
                            'exp' => $expediente,
                            'role' => $role,
                        ];
                        return response()->json(($response));
                    }
                    if ($expediente->exptipoproce_id ==  1) {
                        if ($expediente->expestado_id != 5 and $expediente->expestado_id != 2) {
                            if ($expediente->getDocenteAsig()->name == 'Sin asignar') {
                                $asignacion_caso = $expediente->getAsignacion();
                                $expediente->asigDocente($asignacion_caso);
                            }
                        }
                    }
                }
                if ((count($acts) > 0 || count($reqs) > 0) and $request->new_expestado == 4) {
                    if ($expediente->exptipoproce_id != 1) {
                        $mensaje = '';
                        if (count($reqs) > 0) $mensaje .= 'Hay ' . count($reqs) . ' requerimientos que requieren ser revisados <br>';
                        if (count($acts) > 0) $mensaje .= 'Hay ' . count($acts) . ' actuaciones que requieren ser revisadas';

                        $response = [
                            'mensaje' => $mensaje,
                            'guardado' => false,
                            'exp' => $expediente,
                        ];
                        return response()->json(($response));
                    } else {
                        $estadoCaso = $this->estadoCasoService->store($request);
                        $expediente->save();
                        $response = [
                            'mensaje' => 'El Caso fue actualizo con éxito',
                            'guardado' => true,
                            'exp' => $expediente,
                            'role' => $role,
                        ];
                        return response()->json(($response));
                    }
                } else {
                    if ($request->new_expestado == 3) {
                        $nota = $expediente->get_has_nota_final();
                        if (count($nota) > 0) {
                            $response = [
                                'mensaje' => 'El caso ya fue evaluado con una nota final, debe eliminar...',
                                'guardado' => false,
                                'exp' => $expediente,
                            ];
                            return response()->json(($response));
                        }
                    }
                    if ($request->new_expestado == 5) {
                        $nota = $expediente->get_has_nota_final();
                        if (count($nota) <= 0) {
                            $segmento = $this->segmentosService->getSegmentoActivo();
                            if ($segmento) {
                                $data = [
                                    'ntaaplicacion' => 0,
                                    'ntaconocimiento' => 0,
                                    'ntaetica' => 0,
                                    'ntaconcepto' => 'Evaluado por el sistema - Cambio de estado por administrador',
                                    'orgntsid' => '1',
                                    'segid' => $segmento->id,
                                    'perid' => $segmento->perid,
                                    'tpntid' => '1',
                                    'expidnumber' => $expediente->expid,
                                    'estidnumber' => $expediente->expidnumberest,
                                    'docidnumber' => Auth::user()->idnumber,
                                    'tbl_org_id' => $expediente->id,
                                ];
                                $expediente->asignarNotas($data);
                            } else {
                                $response = [
                                    'mensaje' => 'No se puede asignar notas, debe activar un corte',
                                    'guardado' => false,
                                    'exp' => $expediente,
                                ];
                                return response()->json(($response));
                            }
                        }
                    }
                    $estadoCaso = $this->estadoCasoService->store($request);
                    $expediente->save();
                    $response = [
                        'mensaje' => 'El Caso fue actualizo con éxito',
                        'guardado' => true,
                        'exp' => $expediente,
                        'role' => $role,
                    ];
                }
            }


            return response()->json(($response));
        }
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

    public function abrir_caso(Request $request)
    {

        $expediente = $this->expedienteService->findWithFilter([
            'expid' => $request->expid
        ]);
     
        if ($expediente) {
            $segmento = $this->segmentosService->find($request->segid);
            if ($segmento) {
                $notas =  $expediente->notas()
                    ->where([
                        'estidnumber' => $expediente->expidnumberest,
                        'orgntsid' => 4,
                        'tpntid' => 1,
                        'segid' => $segmento->id
                    ])->delete();

                $data = [
                    'ntaaplicacion' => $request->ntaaplicacion,
                    'ntaconocimiento' => $request->ntaconocimiento,
                    'ntaetica' => $request->ntaetica,
                    'ntaconcepto' => $request->ntaconcepto,
                    'orgntsid' => $request->orgntsid,
                    'segid' => $request->segid,
                    'perid' => $request->perid,
                    'tpntid' => $request->tpntid,
                    'expidnumber' => $request->expid,
                    'estidnumber' => $expediente->expidnumberest,
                    'docidnumber' => auth()->user()->idnumber,
                    'tbl_org_id' => $expediente->id,
                ];
                $expediente->asignarNotas($data);
                $request['comentario'] = $request->ntaconcepto != "" ? $request->ntaconcepto :"Cerrado despues de vencido el plazo para cierre";
                $request['expidnumber'] = $request->expid;
                $request['ref_estado_id'] = 2;
                $request['ref_motivo_estado_id'] = 8;
                $estadoCaso = $this->estadoCasoService->store($request);
                $expediente->expestado_id = 2;
                $expediente->save();
                return response()->json("Si evaluado");
            } else {
                return response()->json(["No evaluado"]);
            }
        }

        return response()->json(["No se puede evaluar"]);
    }

    public function cerrarCasoNotaMinima(Request $request)
    {
        
        $expediente = $this->expedienteService->findWithFilter([
            'expid' => $request->expid
        ]);
    
         if ($expediente and ($expediente->isValidOpenPeriodo())) {
            $asignacion = $expediente->asignacion;
            $segmento = $this->segmentosService->getSegmentoAsignacion($asignacion);  
            if ($segmento) {
                $notas =  $expediente->notas()
                    ->where([
                        'estidnumber' => $expediente->expidnumberest,
                        'orgntsid' => 4,
                        'tpntid' => 1,
                        'segid' => $segmento->segmento_id
                    ])
                    ->delete();

                $data = [
                    'ntaaplicacion' => $request->ntaaplicacion,
                    'ntaconocimiento' => $request->ntaconocimiento,
                    'ntaetica' => $request->ntaetica,
                    'ntaconcepto' => $request->ntaconcepto,
                    'orgntsid' => $request->orgntsid,
                    'segid' => $segmento->segmento_id,
                    'perid' => $request->perid,
                    'tpntid' => $request->tpntid,
                    'expidnumber' => $expediente->expid,
                    'estidnumber' => $expediente->expidnumberest,
                    'docidnumber' => auth()->user()->idnumber,
                    'tbl_org_id' => $expediente->id,
                ];
                $expediente->asignarNotas($data);
                $request['comentario'] = $request->ntaconcepto != "" ? $request->ntaconcepto :"Cerrado despues de vencido el plazo para cierre";
                $request['expidnumber'] = $request->expid;
                $request['ref_estado_id'] = 2;
                $request['ref_motivo_estado_id'] = 8;
                $estadoCaso = $this->estadoCasoService->store($request);
                $expediente->expestado_id = 2;
                $expediente->save();
                return response()->json("Si evaluado");
            } else {
                return response()->json(["No evaluado"]);
            }
        }

        return response()->json(["No se puede evaluar"]);
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
        //
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
    public function search(Request $request)
    {
        $estadoCaso = EstadoCaso::find($request->id);
        $estadoCaso->estado;
        $estadoCaso->motivo;
        $estadoCaso->user;

        return response()->json($estadoCaso);
    }

    public function cerrarCaso(Request $request)
    {
        // return response()->json($request->all());
        $estadoCaso = $this->estadoCasoService->store($request);
        if ($estadoCaso) {
            $expediente = Expediente::where('expid', $request->expidnumber)->first();
            $expediente->expestado_id = $request->ref_estado_id;
            $expediente->save();
        }

        return response()->json($request->all());
    }
}
