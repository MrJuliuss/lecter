<?php

namespace MrJuliuss\Lecter\Facades;

use Illuminate\Support\Facades\Facade;

class Lecter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lecter';
    }
}