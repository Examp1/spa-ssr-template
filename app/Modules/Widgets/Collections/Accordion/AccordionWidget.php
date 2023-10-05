<?php

namespace App\Modules\Widgets\Collections\Accordion;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class AccordionWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Акордеон';

    public static string $preview = 'accordion.jpg';

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
                'type'  => 'image',
                'name'  => 'image',
                'label' => 'Зображення',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt',
                'alt_value' => '',
            ],
            [
                'type'    => 'title-text-list',
                'name'    => 'list',
                'label'   => 'Список',
                'class'   => '',
                'rules'   => 'nullable|array',
                'value'   => [],
            ]
        ];
    }
}
