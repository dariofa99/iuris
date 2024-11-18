<?php

namespace App\Repositories;

use App\AdminEncuestas;
use App\ConcEncSatifAditionalData;
use App\ConcEncuestaSatisf;
use App\ExpEncSatifAditionalData;
use App\ExpEncuestaSatisf;
use App\Mail\ConfirmarCorreo;
use App\Mail\RegConcEncuestaSatSuccess;
use App\ReferencesData;
use App\Sede;
use App\Services\ConcEncuSatisfaccionService;
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

class ExpEncuSatisfaccionRepository extends BaseRepository implements ExpEncuSatisfaccionService
{


  private $verifyStatus;
  public function __construct(ExpEncuestaSatisf $model)
  {
    parent::__construct($model);
    $this->model = $model;
  }


  public function store(Request $request)
  {

     try { 
     
      $encuestaAct = AdminEncuestas::where("activo",1)->first();

      if($encuestaAct){
        $token = str_replace("/", "&&&",Crypt::encryptString(time()));
        $token = rtrim($token, '=');
        $encuesta =  ExpEncuestaSatisf::create([
          'fecha_registro' => date('Y-m-d'),
          'exp_id' => $request->has('exp_id') ? $request->input('exp_id') : null,
          'user_id' => $request->has('user_id') ? $request->input('user_id') : null,
          'token' => $token,
          'encuesta_id' => $encuestaAct->id
        ]); 
        if ($request->has('data') and is_array($request->data)) {
          $requestData = $request->data;
          foreach ($request->data as $key => $rq) {
            $rq['exp_satisf_id'] = $encuesta->id;
            $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
            if ($ref_data) {
              $this->storeData($ref_data, $rq, $requestData);
            }
          }
          //Mail::to(auth()->user()->email)->send(new RegConcEncuestaSatSuccess());
        }
        return $encuesta;
      }


      return false;
     } catch (\Throwable $th) {
      return $th->getMessage();
    } 


    /*  */
  }

  public function update(Request $request,$encuesta): ExpEncuestaSatisf
  {
    $encuesta->fill($request->all());
    $encuesta->save();
    if ($request->has('data') and is_array($request->data)) {
      $requestData = $request->data;
      foreach ($request->data as $key => $rq) {
        $rq['exp_satisf_id'] = $encuesta->id;
        $ref_data = ReferencesData::where(['name' => $rq['name'], 'section' => $rq['section']])->first();
        if ($ref_data) {
          $this->storeData($ref_data, $rq, $requestData);
        }
      }
      Mail::to(auth()->user()->email)->send(new RegConcEncuestaSatSuccess());
    }
    return $encuesta;
  }

  protected function storeData($ref_data, $request, $requestData)
  {


    if ($ref_data->type_data_id == 170) {
      $data = ExpEncSatifAditionalData::where([
        'reference_data_id' => $ref_data->id,
        'exp_satisf_id' => $request['exp_satisf_id']
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
      $delete = ExpEncSatifAditionalData::whereIn('reference_data_option_id', $itemsDiff)
        ->where([
          'reference_data_id' => $ref_data->id,
          'exp_satisf_id' => $request['exp_satisf_id']
        ])->delete();

      $data = ExpEncSatifAditionalData::where([
        'reference_data_option_id' => $request["option_id"],
        'reference_data_id' => $ref_data->id,
        'exp_satisf_id' => $request['exp_satisf_id']
      ])->first();
    } else {
      $data = ExpEncSatifAditionalData::where([
        'reference_data_id' => $ref_data->id,
        'exp_satisf_id' => $request['exp_satisf_id']
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
        $data = ExpEncSatifAditionalData::create([
          'reference_data_id' => $ref_data->id,
          'reference_data_option_id' => $request["option_id"],
          'exp_satisf_id' => $request["exp_satisf_id"],
          'value' => $request["value"],
          'value_is_other' => array_key_exists('value_is_other', $request) ? $request["value_is_other"] : "",
        ]);
      }
    }
  }
}
