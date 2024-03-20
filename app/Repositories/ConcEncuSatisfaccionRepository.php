<?php

namespace App\Repositories;

use App\ConcEncSatifAditionalData;
use App\ConcEncuestaSatisf;
use App\Mail\ConfirmarCorreo;
use App\ReferencesData;
use App\Sede;
use App\Services\ConcEncuSatisfaccionService;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManagerStatic as Image;

class ConcEncuSatisfaccionRepository extends BaseRepository implements ConcEncuSatisfaccionService
{


  private $verifyStatus;
  public function __construct(ConcEncuestaSatisf $model)
  {
    parent::__construct($model);
    $this->model = $model;
  }


  public function store(Request $request): ConcEncuestaSatisf
  {

    $user =  ConcEncuestaSatisf::create([
      'tipo_usuario_id' => $request->has('tipo_usuario_id') ? $request->input('tipo_usuario_id') : null,
      'conciliacion_id' => $request->has('conciliacion_id') ? $request->input('conciliacion_id') : null,
      'user_id' => $request->has('user_id') ? $request->input('user_id') : null,
      
    ]);

    if ($request->has('data') and is_array($request->data)) {
      $requestData = $request->data;
      foreach ($request->data as $key => $rq) {
        $rq['enc_satisf_id'] = $user->id;
        $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
        if ($ref_data) {
          $this->storeData($ref_data, $rq, $requestData);
        }
      }
    }
  
    return $user;
  }



  protected function storeData($ref_data, $request, $requestData)
  {


    if ($ref_data->type_data_id == 170) {
      $data = ConcEncSatifAditionalData::where([
        'reference_data_id' => $ref_data->id,
        'enc_satisf_id' => $request['enc_satisf_id']
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
      $delete = ConcEncSatifAditionalData::whereIn('reference_data_option_id', $itemsDiff)
        ->where([
          'reference_data_id' => $ref_data->id,
          'enc_satisf_id' => $request['enc_satisf_id']
        ])->delete();

      $data = ConcEncSatifAditionalData::where([
        'reference_data_option_id' => $request["option_id"],
        'reference_data_id' => $ref_data->id,
        'enc_satisf_id' => $request['enc_satisf_id']
      ])->first();
    } else {
      $data = ConcEncSatifAditionalData::where([       
        'reference_data_id' => $ref_data->id,
        'enc_satisf_id' => $request['enc_satisf_id']
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
        $data = ConcEncSatifAditionalData::create([
          'reference_data_id' => $ref_data->id,
          'reference_data_option_id' => $request["option_id"],
          'enc_satisf_id' => $request["enc_satisf_id"],
          'value' => $request["value"],
          'value_is_other' => array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
        ]);
      }
    }
  }
}
