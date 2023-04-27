<?php

namespace App\Facades;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class NewPush 
{
    public $r_connection;
    public $channel;
    public $data;
    public function __construct(){
        $this->r_connection = Redis::connection();
    }

    public function channel($channel){
      $this->channel = $channel;
      return $this;
    }

    public function message(array $data){
        $this->data = $data;
        return $this; 
    }

      public function publish(){
          return $this->r_connection->publish('',
              json_encode([
              'channel' => $this->channel,
              'message' =>  $this->data,
              ]));
        }
  

}