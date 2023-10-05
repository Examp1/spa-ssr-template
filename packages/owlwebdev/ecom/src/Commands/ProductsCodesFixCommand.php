<?php

namespace Owlwebdev\Ecom\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Owlwebdev\Ecom\Models\Product;

class ProductsCodesFixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecom:fixproducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate codes for products and prices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $prod_codes = [];

        $all_products = Product::with(['prices' => function ($q) {
            $q->with('attributes');
        }])->get();

        foreach ($all_products as $product) {
            if (empty($product->code)) {
                $product->code = str_pad($product->id, 6, '0', STR_PAD_LEFT);
            }

            $start_code = $product->code;

            if (!in_array($product->code, $prod_codes)) {
                $prod_codes[] = $product->code;
            } else {
                $i = 1;
                while (in_array($product->code, $prod_codes)) {
                    $product->code = $start_code . $i;
                    $i++;
                }
            }

            $product->save();

            //prices
            $price_codes = [];

            foreach ($product->prices as $price) {
                if (empty($price->code)) {
                    $attr_code = '';
                    foreach ($price->attributes as $attr) {
                        $attr_code .= ' ' . $attr->slug;
                    }

                    if (!empty($attr_code)) {
                        $price->code = acronym($attr_code);
                    } else {
                        $price->code = str_pad($price->id, 6, '0', STR_PAD_LEFT);
                    }
                }

                $start_code = $price->code;

                if (in_array($price->code, $price_codes)) {
                    $i = 1;
                    while (in_array($price->code, $price_codes)) {
                        $price->code = $start_code . $i;
                        $i++;
                    }
                }

                $price_codes[] = $price->code;

                $price->save();
            }
        }
    }
}
