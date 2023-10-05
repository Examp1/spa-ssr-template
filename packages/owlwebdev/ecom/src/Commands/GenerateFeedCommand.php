<?php

namespace Owlwebdev\Ecom\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Owlwebdev\Ecom\Models\Category;
use Owlwebdev\Ecom\Models\Product;

class GenerateFeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecom:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate feed';

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
        $categories = [];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
            '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . PHP_EOL .
            '<channel>' . PHP_EOL .
            '<title>' . config('app.name')  . '</title>' . PHP_EOL .
            '<link>' . config('app.url') . '</link>' . PHP_EOL .
            '<description>' . config('app.name') . config('app.url') . '</description>' . PHP_EOL;

        $list = Category::all();

        foreach ($list as $item) {
            $categories[$item->id] = [
                'parent_id' => $item->parent_id,
                'name'      => $item->ean,
            ];
        }

        // skip categories
        // $not_list = Category::where('id', 18)->orWhere('id', 862)->get()->pluck('id')->toArray(); // first lvl
        // $not_list = array_merge(Category::whereIn('parent_id', $not_list)->pluck('id')->toArray(), $not_list); //second lvl
        // $not_list = array_merge(Category::whereIn('parent_id', $not_list)->pluck('id')->toArray(), $not_list); //third lvl

        $list = Product::getQueryWithPrices()
            ->with(['category' => function ($query) {
                $query->withTranslation();
            }])
            // ->whereNotIn('products.category_id', $not_list)
            // ->selectRaw('ps.price as special_price, ' .
            // 'products.price, products.alias, products.id, products.image, products.sku, products.quantity, ' .
            // 'products.minimum, products.status, products.sort_order, products.category_id, products.ean, ' .
            // 'product_index.sort_order')
            ->get();

        foreach ($list as $item) {
            $stock = 'in stock';
            $parent_id = $item->category->id;
            do {
                $product_type = $categories[$parent_id]['name'];
                $parent_id = $categories[$parent_id]['parent_id'];
            } while ($parent_id && isset($categories[$parent_id]));

            $xml .= '<item>
<g:id>' . $item->code . '</g:id>
<g:title>' . htmlspecialchars($item->name) . '</g:title>
<g:description>' . htmlspecialchars(strip_tags($item->description)) . '</g:description>
<g:link>' . $item->frontLink() . '</g:link>
<g:image_link>' . htmlspecialchars(get_image_uri($item->image)) . '</g:image_link>
<g:availability>' . $stock . '</g:availability>
<g:price>' . number_format(((is_null($item->old_price) /*without special*/) ? $item->price : $item->old_price), 2) . ' UAH</g:price>' .
            /* with special */
            (!is_null($item->old_price) ? ('<g:sale_price>' . number_format($item->price) . ' UAH</g:sale_price>') : '') .
'<g:brand>' . htmlspecialchars($categories[$item->category_id]['name']) . '</g:brand>
<g:product_type>' . $product_type . '</g:product_type>
<g:condition>new</g:condition>';
            // if (!is_null($item->sort_order)) { // Для товаров на главной добавляем тег <custom_label_0>
            //     $xml .= chr(13) . chr(10) . '<g:custom_label_0>' . htmlspecialchars($item->ean) . '</g:custom_label_0>' . chr(13) . chr(10);
            // }
            $xml .= '</item>' . PHP_EOL;
        }

        $xml .= '</channel></rss>';


        try {
            file_put_contents(public_path('google_feed.xml'), $xml);
        } catch (\Throwable $e) {
            Log::error('Cant save feed file', $e);
        }

        try {
            chmod(public_path('google_feed.xml'), 0665);
        } catch (\Throwable $e) {
            Log::error('Cant set permissions on feed file', $e);
        }

        Log::info('feed generated', []);
    }
}
