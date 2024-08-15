<?php

namespace App\Services;


use App\ExpEncuestaSatisf;
use Illuminate\Http\Request;


interface ExpEncuSatisfaccionService {
 
    public function store(Request $request):ExpEncuestaSatisf;
    public function update(Request $request,$encuesta):ExpEncuestaSatisf;
        
   
}
?>