<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModelExport implements FromCollection, WithHeadings
{
    private $model;
    private $heading;

    public function __construct($model,$heading)
    {
        $this->model = $model;
        $this->heading = $heading;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->model;
    }

    public function headings(): array
    {
        return $this->heading;
    }
}
