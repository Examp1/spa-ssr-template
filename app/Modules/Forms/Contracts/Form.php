<?php

namespace App\Modules\Forms\Contracts;

interface Form
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
