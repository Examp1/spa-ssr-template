<?php

namespace Owlwebdev\Ecom\Exports;

use Owlwebdev\Ecom\Models\Product;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
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

class ProductExport implements FromQuery, Responsable, ShouldQueue, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithMapping, WithStrictNullComparison
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

        $this->fileName = 'products_full_' . Carbon::now()->format('dmYHs') . '.xlsx';
    }

    public function query()
    {
        $status = $this->params['status'] ?? '';
        $categories = $this->params['categories'] ?? [];
        $quantity = $this->params['quantity'] ?? '';

        return Product::query()
            ->withTranslation()
            ->with(['prices', 'images'])
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
            'Назва товару та опції',
            'Артикул',
            'Головна категорія',
            'В які категорії входить',
            'Короткий опис товару',
            'Статус',
            'Ціна товару або опцій',
            'Ціна стара товару або опцій',
            'Собівартість',
            'Наявність(кількість)',
            'Валюта',
            'Дата оновлення',
            'Посилання на зображення'
        ];
    }

    public function map($product): array
    {
        $result = [];
        if ($product->prices->isEmpty()) {
            $img_str = '';

            $images = $product->images->map(function ($one) {
                return get_image_uri($one->image);
            })->toArray();

            if (!empty($images)) {
                $img_str = implode(',', $images);
            }

            $result[] = [
                $product->name, // A
                $product->code, // B
                $product->category_id, // C
                implode(',', $product->categories->pluck('id')->toArray()), // D
                $product->info, // E
                (int)$product->status, // F
                $product->price, // G
                $product->old_price, // H
                $product->cost, // I
                $product->quantity, // J
                $product->currency, // K
                $product->updated_at, // L
                $img_str, // M

            ];
        } else { // with prices
            $img_str = '';
            $options_imgs = [];

            $images = $product->images->map(function ($one) use ($options_imgs) {
                if ($one->price_id) {
                    $options_imgs[$one->price_id] = get_image_uri($one->image);
                    return false;
                } else {
                    return get_image_uri($one->image);
                }
            })
            ->reject(function ($value) {
                return $value === false;
            })
            ->toArray();

            if (!empty($images)) {
                $img_str = implode(',', $images);
            }

            $result[] = [
                $product->name, // A
                $product->code, // B
                $product->category_id, // C
                implode(',', $product->categories->pluck('id')->toArray()), // D
                $product->info, // E
                (int)$product->status, // F
                '', // $product->price, // G
                '', // $product->old_price, // H
                '', // $product->cost, // I
                '', // $product->quantity, // J
                $product->currency, // K
                $product->updated_at, // L
                $img_str, // M
            ];

            foreach ($product->prices as $option) {
                $img_str = '';

                if (isset($options_imgs[$option->id]) && !empty($options_imgs[$option->id])) {
                    $img_str = implode(',', $options_imgs[$option->id]);
                }

                $result[] = [
                    $product->name . ' - ' . $option->name, // A
                    $product->code . '-' . $option->code, // B
                    '', // C
                    '', // D
                    '', // E'
                    (int)$option->status, // F
                    $option->price, // G
                    $option->old_price, // H
                    $option->cost, // I
                    $option->count, // J
                    '', // $product->currency, // K
                    '', // $option->updated_at, // L
                    $img_str,
                ];
            }
        }

        return $result;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Export failed', $exception);
    }
};
