<?php

namespace Owlwebdev\Ecom\Imports;

use Owlwebdev\Ecom\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Owlwebdev\Ecom\Models\ProductPrices;
use Illuminate\Support\Facades\Log;

class ProductImport implements ToCollection, SkipsEmptyRows, WithHeadingRow, WithChunkReading, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $codes = $this->parseCode($row['code']);
            if ($codes['is_product']) {

                $up_data = [
                    'status' => $row['status'],
                ];

                if (!empty($row['price'])) {
                    $up_data['price'] = $row['price'];
                }

                if (!empty($row['old_price'])) {
                    $up_data['old_price'] = $row['old_price'];
                }

                if (!empty($row['quantity'])) {
                    $up_data['quantity'] = $row['quantity'];
                }

                if (!empty($row['cost'])) {
                    $up_data['cost'] = $row['cost'];
                }

                Product::where('code', $codes['product_code'])
                    ->update($up_data);
            } else {
                ProductPrices::whereHas('product', function ($q) use ($codes) {
                    $q->where('code', $codes['product_code']);
                })->where('code', $codes['price_code'])->update([
                    'price' => $row['price'],
                    'old_price' => $row['old_price'],
                    'cost' => $row['cost'] ?: 0,
                    'count' => $row['quantity'] ? : 0,
                    'status' => $row['status'] ?: 0,
                ]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 300;
    }

    private function parseCode(string $code)
    {
        $code_delimiter = '-';
        $result = [
            'is_product' => true,
            'product_code' => $code,
            'price_code' => '',
        ];

        if (strpos($code, $code_delimiter) == false) {
            return $result;
        }

        $code_array = explode($code_delimiter, $code);

        if (isset($code_array[1]) && !empty($code_array[1])) {
            $result['is_product'] = false;
            $result['product_code'] = $code_array[0];
            $result['price_code'] = $code_array[1];
        }

        return $result;
    }
}
