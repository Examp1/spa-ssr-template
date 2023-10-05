<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ModelExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
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

    public function columnFormats(): array
    {
        $columnFormats = [];

        foreach ($this->model[0] as $key => $value) {
            if ($key === 'phone') {
                $columnFormats[$this->getExcelColumn($key)] = NumberFormat::FORMAT_NUMBER;
            }
        }

        return $columnFormats;

    }

    private function getExcelColumn($key)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();

        $columnDimensions = $worksheet->getColumnDimensions();
        $columnIndex = 0;

        foreach ($columnDimensions as $column) {
            $columnIndex++;
            if ($column->getColumnIndex() === $key) {
                return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
            }
        }

        return '';
    }
}
