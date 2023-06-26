<?php
namespace App\Services;

use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\Collection;
interface ChatService {

    public function room(String $room);    

}