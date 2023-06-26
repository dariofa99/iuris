<?php

namespace App\Facades;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class NewChat 
{
    public $username;
    public $room;
    public $idusuario;
    public $correo;
    public $imagen;
    public $can_write = false;
    public function __construct(){

        $this->username= !\Auth::guest() ? \Auth::user()->name : "";
        $this->room='salaatencion';
        $this->idusuario=!\Auth::guest() ? \Auth::user()->idnumber:"";
        $this->correo=!\Auth::guest() ? \Auth::user()->email : "defaultmail@correo.com";
        $this->imagen= !\Auth::guest() ?  url('thumbnails/'.currentUser()->image) : url('thumbnails/default.jpg');
        $this->can_write= !\Auth::guest() ? \Auth::user()->can('escribir_chat_oficina_virtual') : false;
    }

    public function render(){
        $data_chat = [
            'username'=>$this->username,
            'room'=>$this->room,
            'idusuario'=>$this->idusuario,
            'correo'=>$this->correo,
            'imagen'=>$this->imagen,
            'can_write'=>$this->can_write
        ];
         //   dd($data_chat);
         $data_chat= json_encode($data_chat);
         $data_chat = base64_encode($data_chat);
         $data_chat = str_replace("/", "&&&", $data_chat);
         
         return view('recursos.frm_chat_iframe',compact('data_chat'));
    }

    public function username($username){
        $this->username = $username;            
         return $this; 
    }

    public function room($room){
        $this->room=$room; 
        return $this;
    }
    public function idusuario($idusuario){
        $this->idusuario = $idusuario;            
         return $this;
    }
    public function correo($correo){
        $this->correo = $correo;            
         return $this;
    }
    public function imagen($imagen){
        $this->imagen = $imagen;            
         return $this;
    }

    public function can_write($can_write){
        $this->can_write = $can_write;            
         return $this;
    }
  

}