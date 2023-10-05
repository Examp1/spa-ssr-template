<?php

namespace App\Modules\Widgets\Collections\FirstScreen;

use App\Modules\Forms\Models\Form;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class SliderWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Перший екран (Слайдер)';

    public static string $preview = 'first-screen-slider.jpg';

    public static array $groups = [WIDGET_GROUP_LANDING, WIDGET_GROUP_PAGE];

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
        return view('widgets::collections.first-screen-slider.index', [
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
                'type'  => 'title-title-text-image-list',
                'name'  => 'list',
                'label' => 'Список',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
            ]
        ];
    }

    public function adapter($data, $lang)
    {
        $list = $data['list'];

        usort($list, array('App\Service\Adapter', 'sort'));

        $data['list'] = array_values($list);

        return $data;
    }
}
