<?php

namespace App\Services;


use App\ExpEncuestaSatisf;
use Illuminate\Http\Request;


interface ExpEncuSatisfaccionService {
 
    public function store(Request $request);
    public function update(Request $request,$encuesta):ExpEncuestaSatisf;
        
   
}
?>