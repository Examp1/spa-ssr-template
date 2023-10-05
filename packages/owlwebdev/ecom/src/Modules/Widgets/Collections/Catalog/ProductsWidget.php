<?php

namespace App\Modules\Widgets\Collections\Catalog;

use Owlwebdev\Ecom\Models\Product;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class ProductsWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Товари';

    public static string $preview = 'products.jpg';

    public static array $groups = [WIDGET_GROUP_PAGE];

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
        return view('widgets::collections.advantages.index', [
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
                'value'   => 'Популярні товари',
            ],
            [
                'type'  => 'select',
                'name'  => 'type',
                'label' => 'Тип блоку',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => 'slider',
                'list'  => function () {
                    return [
                        'slider' => 'Карусель',
                        'list'   => 'Плитка',
                    ];
                }
            ],
            [
                'type'    => 'products-list',
                'name'    => 'list',
                'label'   => 'Список товарів',
                'class'   => '',
                'rules'   => 'nullable|array',
                'value'   => [],
            ],
        ];
    }

    public function adapter($data, $lang)
    {
        $list  = [];
        $selectIds = [];

        foreach ($data['list'] as $item) {
            $selectIds[] = $item['product_id'];
            $list[$item['product_id']] = null;
        }

        // disable Autoload Translations
        $model = new Product;
        $model->disableAutoloadTranslations();

        $models = $model->getQueryWithPrices()
            ->whereIn('products.id', $selectIds)
            ->active()
            ->get();

        if (count($models) && count($selectIds)) {
            foreach ($models as $product) {
                $list[$product->id] = $product->toArray();
            }
        }

        $data['list'] = array_values($list);

        return $data;
    }
}
