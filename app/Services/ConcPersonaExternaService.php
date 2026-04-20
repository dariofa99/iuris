<?php

namespace App\Services;

use App\ConcPersonasExternas;

use Illuminate\Http\Request;


interface ConcPersonaExternaService {
 
    public function store(Request $request);
    public function update(Request $request,$encuesta):ConcPersonasExternas;
        
   
}
?>