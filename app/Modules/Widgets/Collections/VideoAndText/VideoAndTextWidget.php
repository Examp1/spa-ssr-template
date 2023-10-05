<?php

namespace App\Modules\Widgets\Collections\VideoAndText;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class VideoAndTextWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Відео і текст';

    public static string $preview = 'video-and-text.jpg';

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
        return view('widgets::collections.video-and-text.index', [
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
                'type'  => 'file',
                'name'  => 'video',
                'label' => 'Відео',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'message' => [
                    'video.max' => 'Максимум 255 символів',
                ],
                'value' => '',
            ],
            [
                'type'  => 'image',
                'name'  => 'image',
                'label' => 'Попередній перегляд',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt',
                'alt_value' => '',
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
            ]
        ];
    }
}
