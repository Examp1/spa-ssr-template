<?php

namespace App\Modules\Widgets\Collections\FirstScreen;

use App\Modules\Forms\Models\Form;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class FirstScreenWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Перший екран з фоном';

    public static string $preview = 'first-screen.jpg';

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
        return view('widgets::collections.first-screen.index', [
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
                'name'  => 'image_position',
                'label' => 'Позиція зображення',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => 'M',
                'list'  => function () {
                    return [
                        'left' => 'Ліворуч',
                        'right'  => 'Праворуч',
                    ];
                }
            ],
            [
                'type'  => 'editor',
                'name'  => 'text',
                'label' => 'Текст',
                'class' => '',
                'rules' => 'nullable|string|max:3000',
                'value' => '',
            ],
            [
                'type'  => 'select',
                'name'  => 'with_fon',
                'label' => 'Фон',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        '0' => 'Ні',
                        'image' => 'Зображення',
                        'video' => 'Відео',
                    ];
                }
            ],
            [
                'type'  => 'image',
                'name'  => 'image',
                'label' => 'Фонове зображення',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt',
                'alt_value' => '',
            ],
            [
                'type'  => 'image',
                'name'  => 'image_mob',
                'label' => 'Фонове зображення (моб.)',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt_mob',
                'alt_value' => '',
            ],
            [
                'type'  => 'file',
                'name'  => 'video',
                'label' => 'Фонове відео',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt',
                'alt_value' => '',
            ],
            [
                'type'  => 'image',
                'name'  => 'sticker',
                'label' => 'Стікер',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt',
                'alt_value' => '',
            ],
            [
                'type'  => 'select',
                'name'  => 'widget_type',
                'label' => 'Тип головного екрану',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        'type_1' => 'Текст, кнопки ліворуч',
                        'type_2' => 'Текст ліворуч, кнопки праворуч',
                        'type_3' => 'Текст, кнопки по центру',
                    ];
                }
            ],
            [
                'type'  => 'select',
                'name'  => 'font_color',
                'label' => 'Колір шрифту',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        'white' => 'Білий',
                        'black' => 'Чорний',
                    ];
                }
            ],
            ['separator' => 'Кнопки'],
            [
                'type'  => 'btns-list',
                'name'  => 'btns',
                'label' => '',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
            ]
        ];
    }

    public function adapter($data, $lang)
    {
        $list  = [];

        if(isset($data['btns'])){
            foreach ($data['btns'] as $key => $item) {
                $list[$key] = $item;
                if($item['type_link'] == 'form' && isset($item['form_id'])){
                    $form = Form::query()->where('id', $item['form_id'])->first();

                    $contentData = $form->getData();

                    if(is_array($contentData) && count($contentData)){
                        foreach ($contentData as $qaw => $qwe){
                            $contentData[$qaw]['type'] = 'form-' . $qwe['type'];
                        }
                    }
                    $list[$key]['form_data'] = $contentData;
                }
            }
        }

        $data['btns'] = $list;

        return $data;
    }
}
