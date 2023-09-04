<?php

namespace App\Modules\Widgets\Collections\Blocks;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class BlocksWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Карточки';

    public static string $preview = 'blocks.jpg';

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
        return view('widgets::collections.blocks.index', [
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
                'label' => 'Кількість в ряд',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'list'  => function () {
                    return [
                        '1' => 'по 1 в ряд',
                        '2' => 'по 2 в ряд',
                        '3' => 'по 3 в ряд',
                        '4' => 'по 4 в ряд',
                    ];
                }
            ],
            [
                'type'  => 'select',
                'name'  => 'card_btn_style_type',
                'label' => 'Вид кнопок в карточках',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'list'  => function () {
                    return array_merge(['none' => 'Без кнопки'],config('buttons.type'));
                }
            ],
            [
                'type'  => 'btn-icons',
                'name'  => 'card_btn_style_icon',
                'label' => 'Іконка для кнопок в карточках',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
            ],
            [
                'type'  => 'image-text-link-list',
                'name'  => 'list',
                'label' => 'Список',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
            ]
        ];
    }
}
