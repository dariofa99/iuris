<?php

namespace App\Repositories;

use App\Conciliacion;
use App\Services\ConciliacionesService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;


class ConciliacionesRepository extends BaseRepository implements ConciliacionesService
{

    public function __construct(Conciliacion $estado)
    {
        parent::__construct($estado);
    }
    public function store(Request $request): Conciliacion
    {
        $conciliacion = Conciliacion::create([
            'token' => $request->has('token') ? $request->input('token') : str_replace("/", "", bcrypt(Str::random(5))),
            'num_conciliacion' => $request->has('num_conciliacion') ? $request->input('num_conciliacion') : strtoupper("CCEAH-0-00-00"), //"CCEAH-0-00-00",
            'num_solicitud' => $request->has('num_solicitud') ? $request->input('num_conciliacion') : strtoupper("CCEAH-" . Str::random(7)), //"CCEAH-0-00-00"
            'categoria_id' => $request->has('categoria_id') ? $request->input('categoria_id') : 173,
            'estado_id' => $request->has('estado_id') ? $request->input('estado_id') : 174,
            'periodo_id' => $request->has('periodo_id') ? $request->input('periodo_id') : 1,
            'user_id' => $request->has('user_id') ? $request->input('user_id') : auth()->user()->id
        ]);
        if (session()->has('sede')) {
            $conciliacion->sedes()->attach(session('sede')->id_sede);
        }
        //autor
        $conciliacion->usuarios()->attach(auth()->user()->id, [
            'tipo_usuario_id' => $request->has('tipo_usuario_id') ? $request->input('tipo_usuario_id') : 199,
            'estado_id' => 1
        ]);
        return $conciliacion;
    }
    public function getAllConciliaciones(
        $request,
        $filtro = null,
        $perPage = 10
    ): LengthAwarePaginator {
        $this->applyValidateSede();
        $this->query = $this->query->filter($request);
        $con = $this->query->orderBy('conciliaciones.created_at', 'desc')
            ->paginate(20);
        return $con;
    }

    protected function applyFiltro($query)
    {
        return $query->where(function ($query) {
            if (!currentUser()->can('ver_all_conciliaciones')) {
                return $query->whereHas('usuarios', function ($query1) {
                    $query1->where([
                        'user_id' => auth()->user()->id,
                    ]);
                });
            }
        })->whereHas('sedes', function ($query1) {
            $query1->where([
                'sede_id' => session('sede')->id_sede,
            ]);
        });
    }

    protected function paginates($query, $perPage)
    {
        $page = Paginator::resolveCurrentPage('page');
        $results = $query->paginate($perPage, ['*'], 'page', $page);
        $results->appends(request()->except('page'));
        return $results;
    }

    public function addUser(Conciliacion $conciliacion, Request $request): Conciliacion
    {
        $user = $conciliacion->usuarios()->where([
            'tipo_usuario_id' => $request->tipo_usuario,
            'user_id' => $request->user_id,
        ])->first();
        if (!$user) {
            $conciliacion->usuarios()->attach($request->user_id, [
                'tipo_usuario_id' => $request->tipo_usuario,
                'estado_id' => 1
            ]);
        }
        return $conciliacion;
    }
}
