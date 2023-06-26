<?php

namespace App\Facades;

use App\Services\ChatService;
use App\Traits\ApiChat as TraitsApiChat;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Crypt;

class ApiChat extends Chat implements ChatService
{
    use TraitsApiChat;
   
   

    public function room($room){
        $this->room=$room; 
        return $this;
    }

}