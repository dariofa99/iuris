<?php

namespace App\Services;

use App\ConcEncuestaSatisf;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface ConcEncuSatisfaccionService {
 
    public function store(Request $request);
    public function update(Request $request,$encuesta):ConcEncuestaSatisf;
        
   
}
?>