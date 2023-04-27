<?php

namespace App\Facades\Notas;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\NotaExt
 */
class NotaExtFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'nota';
    }
}
