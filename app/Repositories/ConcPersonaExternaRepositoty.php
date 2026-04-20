<?php

namespace App\Repositories;

use App\AdminEncuestas;
use App\AdminPersonas;
use App\ConcEncSatifAditionalData;
use App\ConcEncuestaSatisf;
use App\ConcPerExtAditionalData;
use App\ConcPersonasExternas;
use App\ExpEncSatifAditionalData;
use App\ExpEncuestaSatisf;
use App\Mail\ConfirmarCorreo;
use App\Mail\RegConcEncuestaSatSuccess;
use App\ReferencesData;
use App\Sede;
use App\Services\ConcEncuSatisfaccionService;
use App\Services\ConcPersonaExternaService;
use App\Services\ExpEncuSatisfaccionService;
use Illuminate\Http\Request;
use App\Services\UsersService;
use App\User;
use App\UserAditionalData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class ConcPersonaExternaRepositoty extends BaseRepository implements ConcPersonaExternaService
{


  private $verifyStatus;
  public function __construct(ConcPersonasExternas $model)
  {
    parent::__construct($model);
    $this->model = $model;
  }


  public function store(Request $request)
  {

    try {

      //$encuestaAct = AdminPersonas::find($request->input('persona_externa_id'));

      $encuesta =  ConcPersonasExternas::find($request->input('persona_externa_id'));
      if ($encuesta == null) {

        $encuesta = ConcPersonasExternas::create([
          'fecha_registro' => $request->has('fecha_registro') ? $request->input('fecha_registro') : Carbon::now(),
          'conciliacion_id' => $request->has('conciliacion_id') ? $request->input('conciliacion_id') : null,
          'persona_externa_id' => $request->has('persona_externa_id') ? $request->input('persona_externa_id') : null,
          'user_id' => $request->has('user_id') ? $request->input('user_id') : auth()->user()->id,
        ]);
        
      }
      if ($request->has('data') and is_array($request->data)) {
        $requestData = $request->data;
        foreach ($request->data as $key => $rq) {
          $rq['concpersext_id'] = $encuesta->id;
          $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
          if ($ref_data) {
            $this->storeData($ref_data, $rq, $requestData);
          }
        }
        //Mail::to(auth()->user()->email)->send(new RegConcEncuestaSatSuccess());
      }
      return $encuesta;
    } catch (\Throwable $th) {
      return $th->getMessage();
    }


    /*  */
  }

  public function update(Request $request, $encuesta): ConcPersonasExternas
  {
    $encuesta->fill($request->all());
    $encuesta->save();
    if ($request->has('data') and is_array($request->data)) {
      $requestData = $request->data;
      foreach ($request->data as $key => $rq) {
        $rq['concpersext_id'] = $encuesta->id;
        $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
        if ($ref_data) {
          $this->storeData($ref_data, $rq, $requestData);
        }
      }
      // Mail::to(auth()->user()->email)->send(new RegConcEncuestaSatSuccess());
    }
    return $encuesta;
  }

  protected function storeData($ref_data, $request, $requestData)
  {


    if ($ref_data->type_data_id == 170) {
      $data = ConcPerExtAditionalData::where([
        'reference_data_id' => $ref_data->id,
        'concpersext_id' => $request['concpersext_id']
      ])->get();
      $itemsInData = [];
      foreach ($data as $key => $value) {
        $itemsInData[] = $value->reference_data_option_id;
      }
      $itemsInRequest = [];
      foreach ($requestData as $key => $value) {
        $itemsInRequest[] = $value['option_id'];
      }
      $itemsDiff = array_diff($itemsInData, $itemsInRequest);
      $delete = ConcPerExtAditionalData::whereIn('reference_data_option_id', $itemsDiff)
        ->where([
          'reference_data_id' => $ref_data->id,
          'concpersext_id' => $request['concpersext_id']
        ])->delete();

      $data = ConcPerExtAditionalData::where([
        'reference_data_option_id' => $request["option_id"],
        'reference_data_id' => $ref_data->id,
        'concpersext_id' => $request['concpersext_id']
      ])->first();
    } else {
      $data = ConcPerExtAditionalData::where([
        'reference_data_id' => $ref_data->id,
        'concpersext_id' => $request['concpersext_id']
      ])->first();
    }
    if ($data) {
      $data->fill([
        'value' => $request["value"],
        'reference_data_option_id' => $request["option_id"],
        'value_is_other' => array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
      ]);
      $data->save();
    } else {
      if (array_key_exists('option_id', $request) and $request["option_id"] != null) {
        $data = ConcPerExtAditionalData::create([
          'reference_data_id' => $ref_data->id,
          'reference_data_option_id' => $request["option_id"],
          'concpersext_id' => $request["concpersext_id"],
          'value' => $request["value"],
          'value_is_other' => array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
        ]);
      }
    }
  }
}
