<?php

namespace App\Modules\Widgets\Contracts;

interface Widget
{
    /**
     * @return mixed
     */
    public function execute();

    /**
     * @return array
     */
    public function fields(): array;
}
