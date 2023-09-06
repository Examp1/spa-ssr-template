<?php

namespace App\Modules\Widgets\Collections\Ticker;

use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class TickerWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Біжучий рядок';

    public static string $preview = 'ticker.jpg';

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
        return view('widgets::collections.ticker.index', [
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
                'type'  => 'select',
                'name'  => 'text_color',
                'label' => 'Колір тексту',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        'black' => 'Чорний',
                        'white' => 'Білий'
                    ];
                }
            ],
            ['separator' => 'Рядок 1'],
            [
                'type'  => 'ticker-list',
                'name'  => 'list',
                'label' => 'Список',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
            ],
            ['separator' => 'Рядок 2'],
            [
                'type'  => 'ticker-list',
                'name'  => 'list-2',
                'label' => 'Список',
                'class' => '',
                'rules' => 'nullable|array',
                'value' => [],
            ]
        ];
    }

    public function adapter($data, $lang)
    {
        $list  = [];
        $list2 = [];

        foreach ($data['list'] as $item) {
            if (array_keys($item)[0] === 'text') {
                $list[] = [
                    'type' => 'text',
                    'data' => $item['text']
                ];
            } else {
                $list[] = [
                    'type' => 'image',
                    'data' => $item['image']
                ];
            }
        }

        if (isset($data['list-2'])) {
            foreach ($data['list-2'] as $item) {
                if (array_keys($item)[0] === 'text') {
                    $list2[] = [
                        'type' => 'text',
                        'data' => $item['text']
                    ];
                } else {
                    $list2[] = [
                        'type' => 'image',
                        'data' => $item['image']
                    ];
                }
            }

            $data['list-2'] = $list2;
        }

        $data['list'] = $list;

        return $data;
    }
}
