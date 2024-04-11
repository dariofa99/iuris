<?php

namespace App\Services;

interface VacacionesService {

    
    public function getByDates(array $request);
    public function getDays($vacaciones);
       
}
?>