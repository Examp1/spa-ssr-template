<?php

namespace App\Modules\Widgets\Collections\Contacts;

use App\Models\Settings;
use App\Service\Adapter;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class ContactsWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Контакти';

    public static string $preview = '';

    public static array $groups = [WIDGET_GROUP_PAGE];

    /**
     * @var array
     */
    public array $data;

    private Adapter $adapter;

    /**
     * Widget constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [], Adapter $adapter)
    {
        $this->data = $data;
        $this->adapter = $adapter;
    }

    public function execute()
    {
        return null;
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
                'name'  => 'top_separator',
                'label' => 'Верхній роздільник',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        'M'   => 'M',
                        'S'   => 'S',
                        'L'   => 'L',
                        'NON' => 'NON',
                    ];
                }
            ],
            [
                'type'  => 'select',
                'name'  => 'bottom_separator',
                'label' => 'Нижній роздільник',
                'class' => '',
                'rules' => 'nullable|string|max:255',
                'value' => '',
                'list'  => function () {
                    return [
                        'M'   => 'M',
                        'S'   => 'S',
                        'L'   => 'L',
                        'NON' => 'NON',
                    ];
                }
            ],
        ];
    }

    public function adapter($data, $lang)
    {
        $title = $data['title'] ?? '';
        $model = Settings::query()
            ->where('code', 'contacts')
            ->select([
                'code', 'value', 'lang'
            ])
            ->get()
            ->groupBy('lang')->transform(function ($item, $k) {
                return $item->groupBy('code');
            })
            ->toArray();

        if (!$model)
            return [];

        $data = $this->adapter->prepareSettingsResults($model, $lang);
        $data['title'] = $title;
        return $data;
    }
}
