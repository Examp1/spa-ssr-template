<?php

namespace App\Modules\Widgets\Collections\ContactsTabs;

use App\Modules\Setting\Setting;
use App\Modules\Widgets\Contracts\Widget as WidgetInterface;

class ContactsTabsWidget implements WidgetInterface
{
    /**
     * @var string
     */
    public static string $name = 'Контакти з табами';

    public static string $preview = 'contacts-tabs.jpg';

    public static array $groups = [WIDGET_GROUP_LANDING,WIDGET_GROUP_PAGE];

    /**
     * @var array
     */
    public array $data;
    public $contacts;
    public $tabs;

    /**
     * Widget constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->contacts = app(Setting::class)->get('contacts');
        $this->tabs = [];

        try {
            $this->contacts = json_decode($this->contacts,true);
        } catch (\Throwable $e){
            $this->contacts = [];
        }

        if($this->contacts && count($this->contacts)){
            foreach ($this->contacts as $contact){
                $this->tabs[$contact['slug']] = $contact['name'];
            }
        }
    }

    public function execute()
    {
        return view('widgets::collections.contacts-tabs.index', [
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
        ];
    }

    public function adapter($data, $lang)
    {
        $contacts = [];

        foreach ($this->contacts as $contact){
            $contacts[] = $contact;
        }

        $data['contacts'] = $contacts;


        return $data;
    }
}
