<?php

namespace App\Modules\Widgets\Collections\ImageAndText;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class ImageAndTextWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Зображення та текст';

    public static string $preview = 'image-and-text.jpg';

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
        return view('widgets::collections.image-and-text.index', [
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
                'type'  => 'editor',
                'name'  => 'text',
                'label' => 'Текст',
                'class' => '',
                'rules' => 'nullable|string|max:3000',
                'message' => [
                    'text.max' => 'Максимум 3000 символів',
                ],
                'value' => '',
            ],
            [
                'type'  => 'select',
                'name'  => 'position',
                'label' => 'Позиція зображення',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        'left'  => 'Ліворуч',
                        'right' => 'Праворуч'
                    ];
                }
            ],
            [
                'type'  => 'image',
                'name'  => 'image2',
                'label' => 'Зображення',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt_test',
                'alt_value' => '',
            ]
        ];
    }
}
