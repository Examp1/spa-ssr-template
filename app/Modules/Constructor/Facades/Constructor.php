<?php

namespace App\Modules\Constructor\Facades;

use Illuminate\Support\Facades\Facade;

class Constructor extends Facade
{
    /**
     * Constructor facade
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Modules\Constructor\Constructor::class;
    }
}
