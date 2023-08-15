<?php

namespace App\Modules\Widgets\Collections\SeeAlso;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;
use Illuminate\Support\Facades\Log;

class SeeAlsoWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Дивіться також';

    public static string $preview = 'see-also.jpg';

    public static array $groups = [WIDGET_GROUP_LANDING,WIDGET_GROUP_PAGE];

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
        return view('widgets::collections.see-also.index', [
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
            ['separator' => 'Кнопка'],
            [
                'type'  => 'text',
                'name'  => 'btn_name',
                'label' => 'Текст',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value' => '',
            ],
            [
                'type'  => 'text',
                'name'  => 'btn_link',
                'label' => 'Посилання',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value' => '',
            ],
            [
                'type'  => 'select',
                'name'  => 'btn_style',
                'label' => 'Стиль',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'list'  => function () {
                    return config('buttons.type');
                }
            ],
            [
                'type'  => 'btn-icons',
                'name'  => 'btn_icon',
                'label' => 'Іконка',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
            ],
//            [
//                'type'  => 'interlink', // одиночное поле выбора ссылки
//                'name'  => 'link',
//                'label' => 'Ссылка',
//                'rules' => 'nullable|array',
//                'value' => [],
//            ],
            ['separator' => 'Список'],
            [
                'type'  => 'title-interlink-with-preview-list',  // добавляемый список полей выбора ссылки
                'name'  => 'list',
                'label' => '',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
            ]
        ];
    }

    public function adapter($data, $lang)
    {
        $list = [];

        foreach ($data['list'] as $key => $item) {
            $interlink = get_interlink($item, $lang);

            $list[$key] = array_merge([
                'title' => $item['title'],
                'text'  => $item['text'],
                'image' => $item['image']
            ], $interlink);

            if(isset($interlink['public_date']) && $interlink['public_date']){
                $list[$key]['public_date'] = $interlink['public_date'];
            }
        }

        $data['list'] = $list;

        $data['button'] = [
            'name'  => $data['btn_name'],
            'link'  => $data['btn_link'],
            'style' => $data['btn_style'],
            'icon'  => $data['btn_icon'],
        ];

        unset($data['btn_name']);
        unset($data['btn_link']);
        unset($data['btn_style']);
        unset($data['btn_icon']);

        return $data;
    }
}
