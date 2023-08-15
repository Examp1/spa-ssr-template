<?php

namespace App\Modules\Forms\Facades;

use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    /**
     * Widget facade
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Modules\Forms\Form::class;
    }
}
