<?php

namespace App\Modules\Widgets\Collections\Advantages;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class AdvantagesWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Переваги';

    public static string $preview = 'advantages.jpg';

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
                'value'   => '',
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
                'type'    => 'image-title-text-list',
                'name'    => 'list',
                'label'   => 'Список',
                'class'   => '',
                'rules'   => 'nullable|array',
                'value'   => [],
            ],
            [
                'type'  => 'select',
                'name'  => 'items_in_row',
                'label' => 'В ряду по',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ];
                }
            ]
        ];
    }
}
