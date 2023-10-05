<?php

namespace App\Modules\Widgets\Collections\Catalog;

use Owlwebdev\Ecom\Models\Category;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class CategoriesWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Категорії';

    public static string $preview = 'categories.jpg';

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
                'value'   => 'Категорії',
            ],
            [
                'type'    => 'categories-list',
                'name'    => 'list',
                'label'   => 'Список Категорій',
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
            $selectIds[] = $item['category_id'];
            $list[$item['category_id']] = null;
        }

        $categories = Category::query()
            ->whereIn('categories.id', $selectIds)
            ->leftJoin('category_translations', 'category_translations.category_id', '=', 'categories.id')
            ->where('category_translations.lang', $lang)
            ->where('category_translations.status_lang', 1)
            ->select([
                'categories.slug',
                'category_translations.name AS transName',
                'category_translations.image AS image',
                'categories.id as id',
            ])
            ->active()
            ->get();

        if (count($categories) && count($selectIds)) {
            foreach ($categories as $category) {
                $list[$category->id] = [
                    'slug' => $category->slug,
                    'url'  => $category->frontLink(),
                    'name' => $category->transName,
                    'img'  => $category->image,
                ];
            }
        }

        $data['list'] = array_values($list);

        return $data;
    }
}
