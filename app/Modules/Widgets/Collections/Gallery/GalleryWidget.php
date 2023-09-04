<?php

namespace App\Modules\Widgets\Collections\Gallery;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class GalleryWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Галерея';

    public static string $preview = 'gallery.jpg';

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
        return view('widgets::collections.gallery.index', [
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
                'type'  => 'text',
                'name'  => 'title',
                'label' => 'Заголовок',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value' => '',
            ],
            [
                'type'  => 'select',
                'name'  => 'title_font_size',
                'label' => 'Розмір шрифту заголовка',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => 'M',
                'list'  => function () {
                    return [
                        'normal' => 'Звичайний',
                        'small'  => 'Маленький',
                    ];
                }
            ],
            [
                'type'  => 'image-title-text-list',
                'name'  => 'list',
                'label' => 'Список',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
            ]
        ];
    }

    public function adapter($data, $lang)
    {
        $list = [];

        foreach ($data['list'] as $item) {
            $list[] = [
                'title'     => $item['title'],
                'text'      => $item['text'],
                'image'     => $item['image'],
            ];
        }

        $data['list'] = collect($list);

        return $data;
    }
}
