<?php

namespace App\Modules\Widgets\Collections\ImageVideoAndText;

use App\Modules\Forms\Models\Form;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class ImageVideoAndTextWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Зображення/відео і текст';

    public static string $preview = 'image-video-and-text.jpg';

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
        return view('widgets::collections.image-video-and-text.index', [
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
                'type'  => 'text',
                'name'  => 'subtitle',
                'label' => 'Підзаголовок',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value' => '',
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
                'alt_name' => 'alt_test',
                'alt_value' => '',
            ],
            [
                'type'  => 'image',
                'name'  => 'image_mob',
                'label' => 'Зображення (моб.)',
                'class' => '',
                'rules' => 'nullable|string',
                'value' => '',
                'alt_name' => 'alt_test',
                'alt_value' => '',
            ],
            [
                'type'  => 'text',
                'name'  => 'video_link',
                'label' => 'Відео (посилання)',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'message' => [
                    'title.max' => 'Максимум 255 символів',
                ],
                'value' => '',
            ],
            [
                'type'  => 'select',
                'name'  => 'position',
                'label' => 'Позиція зображення',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        'left'  => 'Ліворуч',
                        'right' => 'Праворуч'
                    ];
                }
            ],
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
                    $list[$key]['form_btn_name'] = $form->btn_name;
                }
            }
        }

        $data['btns'] = $list;

        return $data;
    }
}
