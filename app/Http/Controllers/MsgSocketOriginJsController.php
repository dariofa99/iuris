<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facades\App\Facades\NewPush;

class MsgSocketOriginJsController extends Controller
{
    public function postSend(Request $request){
        if (isset($request->room) and isset($request->data)) {
            if (is_array($request->room) and is_array($request->data)) {
                foreach ($request->room as $key => $value) {
                    NewPush::channel($value)
                    ->message($request->data)
                    ->publish();
                }
            } else {
                return "Los elementos deben ser array";
            }
        } else {
            return "Datos insuficientes";
        }

    }
}
