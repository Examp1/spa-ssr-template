<?php

namespace Owlwebdev\Ecom\Exports;

use Owlwebdev\Ecom\Models\Product;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Log;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Carbon;
use Throwable;

class ProductShortExport implements FromQuery, Responsable, ShouldQueue, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStrictNullComparison
{
    use Exportable;

    /**
     * It's required to define the fileName within
     * the export class when making use of Responsable.
     */
    private $fileName = 'export.xlsx';
    // private $fileName = 'export.csv';

    /**
     * Optional Writer Type
     */
    private $writerType = Excel::XLSX;
    // private $writerType = Excel::CSV;

    /**
     * Optional headers
     */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->fileName = 'products_short_' . Carbon::now()->format('dmYHs') . '.xlsx';
    }

    public function query()
    {
        $status = $this->params['status'] ?? '';
        $categories = $this->params['categories'] ?? [];
        $quantity = $this->params['quantity'] ?? '';

        return Product::query()
            ->withTranslation()
            ->with('prices')
            ->where(function ($q) use ($status, $categories, $quantity) {
                if ($status != '') {
                    $q->where('products.status', $status);
                }

                if ($quantity != '') {
                    switch ($quantity) {
                        case '0':
                            $q->where('products.quantity', 0);
                            break;
                        case '1':
                            $q->where('products.quantity', '>=', 1);
                            break;
                        case '10':
                            $q->where('products.quantity', '<=', 10);
                            break;
                    }
                }

                if (!empty($categories) && is_array($categories)) {
                    $q->whereHas('categories', function ($q) use ($categories) {
                        $q->whereIn('categories.id', $categories);
                    });
                }
            });
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'status',
            'price',
            'old_price',
            'cost',
            'quantity',
            'currency',
            'updated_at',
        ];
    }

    public function map($product): array
    {
        $result = [];

        if ($product->prices->isEmpty()) {
            $result[] = [
                $product->name, // A
                $product->code, // B
                (int)$product->status, // C
                $product->price, // D
                $product->old_price, // E
                $product->cost, // F
                $product->quantity, // G
                $product->currency, // H
                $product->updated_at, // I
            ];
        } else { // with prices
            $result[] = [
                $product->name, // A
                $product->code, // B
                (int)$product->status, // C
                '', // $product->price, // D
                '', // $product->old_price, // E
                '', // $product->cost, // F
                '', // $product->quantity, // G
                $product->currency, // H
                $product->updated_at, // I
            ];

            foreach ($product->prices as $option) {
                $result[] = [
                    $product->name . ' - ' . $option->name,
                    $product->code . '-' . $option->code,
                    (int)$option->status,
                    $option->price,
                    $option->old_price,
                    $option->cost,
                    $option->count,
                    '', // $product->currency,
                    '', // $option->updated_at,
                ];
            }
        }

        return $result;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Export failed', $exception);
    }
};
