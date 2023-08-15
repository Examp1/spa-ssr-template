<?php

namespace App\Modules\Widgets\Facades;

use Illuminate\Support\Facades\Facade;

class Widget extends Facade
{
    /**
     * Widget facade
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Modules\Widgets\Widget::class;
    }
}
