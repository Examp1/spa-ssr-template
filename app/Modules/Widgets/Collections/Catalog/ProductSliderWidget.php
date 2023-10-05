<?php

namespace App\Modules\Widgets\Collections\Catalog;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;
use Owlwebdev\Ecom\Models\Product;

class ProductSliderWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Слайдер товарів у вкладках';

    public static string $preview = 'product-slider.jpg';

    public static array $groups = [WIDGET_GROUP_PAGE, WIDGET_GROUP_LANDING];

    /**
     * @var array
     */
    public array $data;

    /**
     * Widget constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function execute()
    {
        return view('widgets::collections.product-slider.index', [
            'data' => $this->data,
        ]);
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            [
                'type'    => 'text',
                'name'    => 'title',
                'label'   => 'Заголовок',
                'class'   => '',
                'rules'   => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value'   => '',
            ],
            ['separator' => 'Таб 1'],
            [
                'type'    => 'text',
                'name'    => 'tab_1_title',
                'label'   => 'Заголовок',
                'class'   => '',
                'rules'   => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value'   => '',
            ],
            [
                'type'    => 'products-list',
                'name'    => 'tab_1_list',
                'label'   => 'Список продуктів',
                'class'   => '',
                'rules'   => 'nullable|array',
                'value'   => [],
            ],
            ['separator' => 'Таб 2'],
            [
                'type'    => 'text',
                'name'    => 'tab_2_title',
                'label'   => 'Заголовок',
                'class'   => '',
                'rules'   => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value'   => '',
            ],
            [
                'type'    => 'products-list',
                'name'    => 'tab_2_list',
                'label'   => 'Список продуктів',
                'class'   => '',
                'rules'   => 'nullable|array',
                'value'   => [],
            ],
            ['separator' => 'Таб 3'],
            [
                'type'    => 'text',
                'name'    => 'tab_3_title',
                'label'   => 'Заголовок',
                'class'   => '',
                'rules'   => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value'   => '',
            ],
            [
                'type'    => 'products-list',
                'name'    => 'tab_3_list',
                'label'   => 'Список продуктів',
                'class'   => '',
                'rules'   => 'nullable|array',
                'value'   => [],
            ],
        ];
    }

    public function adapter($data, $lang)
    {
        $list1  = [];
        $list2  = [];
        $list3  = [];
        $selectIds1 = [];
        $selectIds2 = [];
        $selectIds3 = [];

        $selectIds1 = collect($data['tab_1_list'])->pluck('product_id');
        $selectIds2 = collect($data['tab_2_list'])->pluck('product_id');
        $selectIds3 = collect($data['tab_3_list'])->pluck('product_id');


        // disable Autoload Translations
        $model = new Product;
        $model->disableAutoloadTranslations();

        $products = $model->getQueryWithPrices()
            ->whereIn('products.id', array_merge($selectIds1->toArray(), $selectIds2->toArray(), $selectIds3->toArray()))
            ->active()
            ->get();

        if (count($products)) {
            foreach ($selectIds1 as $selectId1) {
                $product = $products->where('id', $selectId1)->first();
                if($product) {
                    $list1[$product->id] = $product->toArray();
                }
            }

            foreach ($selectIds2 as $selectId2) {
                $product = $products->where('id', $selectId2)->first();
                if ($product) {
                    $list2[$product->id] = $product->toArray();
                }
            }

            foreach ($selectIds3 as $selectId3) {
                $product = $products->where('id', $selectId3)->first();
                if ($product) {
                    $list3[$product->id] = $product->toArray();
                }
            }
        }

        $data['tab_1_list'] = array_values($list1);
        $data['tab_2_list'] = array_values($list2);
        $data['tab_3_list'] = array_values($list3);

        return $data;
    }
}
