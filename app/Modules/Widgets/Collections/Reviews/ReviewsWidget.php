<?php

namespace App\Modules\Widgets\Collections\Reviews;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;
use Owlwebdev\Ecom\Models\Category;
use Owlwebdev\Ecom\Models\Product;
use Owlwebdev\Ecom\Models\Review;

class ReviewsWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Відгуки';

    public static string $preview = 'reviews.jpg';

    public static array $groups = [WIDGET_GROUP_LANDING];

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
        return view('widgets::collections.accordion.index', [
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
                'name'    => 'count',
                'label'   => 'Кількість відгуків',
                'class'   => '',
                'rules'   => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value'   => '3',
            ],
            [
                'type'  => 'select2',
                'multiple'  => true,
                'name'  => 'categories',
                'label' => 'Категорії продуктів',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
                'list'  => function () {
                    $categories = Category::query()
                        ->active()
                        ->get();

                    $list = [];

                    if($categories){
                        foreach ($categories as $category){
                            $list[$category->id] = $category->getNameWithPath();
                        }
                    }

                    return $list;
                }
            ],
            [
                'type'  => 'select',
                'name'  => 'is_children',
                'label' => 'Дивитись у дочірніх категоріях',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        '0' => 'Ні',
                        '1' => 'Так'
                    ];
                }
            ],
        ];
    }

    public function adapter($data, $lang)
    {
        $query = Review::query();

        if($data['categories']){
            if($data['is_children']){
                // Дивитись у дочірніх категоріях
                $categories = Category::query()
                    ->whereIn('id',$data['categories'])
                    ->get();

                $childCategoriesIds = [];

                foreach ($categories as $category){
                    $childCategoriesIds = array_merge($childCategoriesIds,$category->getAllDescendantIds());
                }

                if(count($childCategoriesIds)){
                    $data['categories'] =  array_map('intval', array_unique(array_merge($data['categories'],$childCategoriesIds)));
                }
            }

            $productIds = Product::query()
                ->whereIn('category_id',$data['categories'])
                ->pluck('id')
                ->toArray();

            $query->whereIn('product_id',$productIds);
        }

        $reviews = $query
            ->limit($data['count'])
            ->orderBy('created_at','desc')
            ->get()
            ->toArray();

        $data['reviews'] = $reviews;

        return $data;
    }
}
