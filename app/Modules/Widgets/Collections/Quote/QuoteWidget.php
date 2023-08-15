<?php

namespace App\Modules\Widgets\Collections\Quote;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class QuoteWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Цитата';

    public static string $preview = 'quote.jpg';

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
        return view('widgets::collections.quote.index', [
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
                'name'  => 'author',
                'label' => 'Автор',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'message' => [
                    'author.max' => 'Максимум 255 символів',
                ],
                'value' => '',
            ],
            [
                'type'  => 'select',
                'name'  => 'type',
                'label' => 'Тип',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'list'  => function () {
                    return [
                        'center'                => 'Текст по центру',
                        'right_with_image_icon' => 'Текст праворуч із зображенням та іконкою',
                        'right_with_image'      => 'Текст праворуч із зображенням',
                        'right_without_image'   => 'Текст праворуч без зображення',
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
                'type'  => 'image',
                'name'  => 'image',
                'label' => 'Зображення',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt_image',
                'alt_value' => '',
            ]
        ];
    }
}
