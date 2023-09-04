<?php

namespace App\Modules\Widgets\Collections\Tables;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class TablesWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Таблиця';

    public static string $preview = 'table.jpg';

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
        return view('widgets::collections.tables.index', [
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
                'type'  => 'select',
                'name'  => 'columns',
                'label' => 'Колонки',
                'class' => 'item-row-template-count',
                'rules' => 'nullable|string',
                'value' => '',
                'list'  => function () {
                    return [
                        '2' => '2 Кол.',
                        '3' => '3 Кол.',
                        '4' => '4 Кол.',
                        '5' => '5 Кол.',
                        '6' => '6 Кол.',
                    ];
                }
            ],
            [
                'type'  => 'select',
                'name'  => 'columns-mob',
                'label' => 'Колонки (моб.)',
                'class' => 'item-row-template-count-mob',
                'rules' => 'nullable|string',
                'value' => '',
                'list'  => function () {
                    return [
                        '2' => '2 Кол.',
                        '3' => '3 Кол.',
                        '4' => '4 Кол.',
                        '5' => '5 Кол.',
                        '6' => '6 Кол.',
                    ];
                }
            ],
            [
                'type'       => 'table-rows-list',
                'name'       => 'list',
                'label'      => 'колонки',
                'class'      => '',
                'rules'      => 'nullable|array',
                'value'      => [],
                'columns'    => [
                    '2' => '2 Кол.',
                    '3' => '3 Кол.',
                    '4' => '4 Кол.',
                    '5' => '5 Кол.',
                    '6' => '6 Кол.',
                ],
                'cols_width' => [
                    'auto' => 'auto',
                    '5%'   => '5%',
                    '10%'  => '10%',
                    '16%'  => '16%',
                    '20%'  => '20%',
                    '25%'  => '25%',
                    '33%'  => '33%',
                    '50%'  => '50%',
                    '60%'  => '60%',
                    '75%'  => '75%',
                    '80%'  => '80%',
                    '90%'  => '90%',
                ],
                'template'   => ''
            ],
            [
                'type'       => 'table-rows-list',
                'name'       => 'list-mob',
                'label'      => 'колонки (моб.)',
                'class'      => '',
                'rules'      => 'nullable|array',
                'value'      => [],
                'columns'    => [
                    '2' => '2 Кол.',
                    '3' => '3 Кол.',
                    '4' => '4 Кол.',
                    '5' => '5 Кол.',
                    '6' => '6 Кол.',
                ],
                'cols_width' => [
                    'auto' => 'auto',
                    '5%'   => '5%',
                    '10%'  => '10%',
                    '16%'  => '16%',
                    '20%'  => '20%',
                    '25%'  => '25%',
                    '33%'  => '33%',
                    '50%'  => '50%',
                    '60%'  => '60%',
                    '75%'  => '75%',
                    '80%'  => '80%',
                    '90%'  => '90%',
                ],
                'template'   => '-mob'
            ]
        ];
    }
}
