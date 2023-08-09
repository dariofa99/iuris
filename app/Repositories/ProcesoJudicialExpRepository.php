<?php

namespace App\Repositories;

use App\ProcesoJudicialExpediente;
use App\Sede;
use App\Services\ProcesoJudicialExpService;
use App\Services\SedesService;
use Illuminate\Http\Request;


class ProcesoJudicialExpRepository extends BaseRepository implements ProcesoJudicialExpService
{

    public function __construct(ProcesoJudicialExpediente $prj)
    {
        parent::__construct($prj);
    }

    public function store(Request $request): ProcesoJudicialExpediente
    {

       $this->model = $this->model->create([
            'fecha' => $request->has('fecha') ? $request->input('fecha') : null,
            'hora' => $request->has('hora') ? $request->input('hora') : null,
            'comentario' => $request->has('comentario') ? $request->input('comentario') : null,
            'estado_id' => $request->has('estado_id') ? $request->input('estado_id') : 1,
            'asig_caso_id' => $request->has('asig_caso_id') ? $request->input('asig_caso_id') : null,
            'user_id' => currentUser()->id,

        ]);
        return $this->model;
    }

    public function saveFile(ProcesoJudicialExpediente $procjudi, Request $request): ProcesoJudicialExpediente
    {

        $file = $procjudi->uploadFile($request->file('fileprocjud'));
        $procjudi->files()->attach($file); 
        return $procjudi;
    }

}
