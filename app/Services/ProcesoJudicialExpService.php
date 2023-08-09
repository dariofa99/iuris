<?php

namespace App\Services;


use App\ProcesoJudicialExpediente;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Collection;

interface ProcesoJudicialExpService {

    
    public function store(Request $request):ProcesoJudicialExpediente;
    public function saveFile(ProcesoJudicialExpediente $procej,Request $request):ProcesoJudicialExpediente;
    
}
?>