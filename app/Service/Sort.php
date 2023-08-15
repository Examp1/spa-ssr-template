<?php


namespace App\Service;


class Sort
{
    public static function cmp($a, $b) {
        return ($a['n'] < $b['n']) ? -1 : 1;
    }

    public static function cmp2($a, $b) {
        return ($a['order'] < $b['order']) ? -1 : 1;
    }
}
