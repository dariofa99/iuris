<?php

namespace App\Services;

interface PausasService {

    
    public function getByAsignacion($asignacion,$data);
    public function getDays($pausas);
       
}
?>