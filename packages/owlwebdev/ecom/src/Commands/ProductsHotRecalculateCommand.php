<?php

namespace Owlwebdev\Ecom\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Owlwebdev\Ecom\Models\Order;
use Owlwebdev\Ecom\Models\Product;

class ProductsHotRecalculateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecom:hotproducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Hot for products';

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
        $all_products = Product::withCount([
            'ordered' => function ($q) {
                $q->whereHas('order', function ($q) {
                    $q->whereIn('orders.order_status_id', Order::PUBLIC_STATUSES);
                });
            },
            'reviews as creviews' => function ($q) {
                $q->active();
            },
        ])->get();

        foreach ($all_products as $product) {
            $product->updateRating();
            $product->hot = $product->ordered_count + $product->creviews;
            $product->saveQuietly();
        }
    }
}
