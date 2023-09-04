<?php

namespace App\Modules\Widgets\Collections\SimpleText;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class SimpleTextWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Довільний текст';

    public static string $preview = 'simple-text.jpg';

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
        return view('widgets::collections.simple-text.index', [
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
                'type' => 'text',
                'name' => 'title',
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
                'type' => 'editor',
                'name' => 'text',
                'label' => 'Текст',
                'class' => '',
                'rules' => 'nullable|string|max:3000',
                'message' => [
                    'text.max' => 'Максимум 3000 символів',
                ],
                'value' => '',
            ]
        ];
    }
}
