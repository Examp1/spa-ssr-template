<?php

namespace App\Modules\Widgets\Collections\TextNColumns;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class TextNColumnsWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = '1-3 колонковий текст';

    public static string $preview = 'text-n-columns.jpg';

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
        return view('widgets::collections.text-n-columns.index', [
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
                'type'  => 'select',
                'name'  => 'title_column_select',
                'label' => 'Колонки',
                'class' => 'item-row-template-count',
                'rules' => 'nullable|string',
                'value' => '',
                'list'  => function () {
                    return [
                        '1' => '1 колонка',
                        '2' => '2 колонки',
                        '3' => '3 колонки',
                    ];
                }
            ],
            [
                'type'  => 'n-columns-list',
                'name'  => 'list',
                'label' => 'Колонки',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
                'columns' => [
                    '1' => '1 колонка',
                    '2' => '2 колонки',
                    '3' => '3 колонки',
                ]
            ]
        ];
    }
}
