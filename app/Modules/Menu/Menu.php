<?php

namespace App\Modules\Menu;

class Menu
{
    public static function get($name,$lang = null)
    {
        if(is_null($lang)) $lang = config('translatable.locale');
        return \App\Models\Menu::getByName($name,$lang);
    }
}
