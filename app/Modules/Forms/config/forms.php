<?php

return [
    /*
    |------------------------------------------------------------------
    | Permissions for manipulation widgets
    |------------------------------------------------------------------
    */
    'permissions'       => [
        'forms.view'   => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
        'forms.create' => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
        'forms.update' => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
        'forms.delete' => function ($user) {
            return true;
            //return $user->isSuperUser() || $user->hasPermission('update_setting');
        },
    ],

    /*
    |------------------------------------------------------------------
    | Middleware for settings
    |------------------------------------------------------------------
    */
    'middleware'        => ['web', 'auth', 'verified'],

    /*
    |------------------------------------------------------------------
    | Uri Route prefix
    |------------------------------------------------------------------
    */
    'uri_prefix'        => 'admin',

    /*
    |------------------------------------------------------------------
    | Route name prefix
    |------------------------------------------------------------------
    */
    'route_name_prefix' => 'admin.',

    /*
    |------------------------------------------------------------------
    | Request lang key
    |------------------------------------------------------------------
    */
    'request_lang_key'  => 'lang',

    'fields' => [
        [
            'label' => 'Заголовок',
            'type' => 'title'
        ],
        [
            'label' => 'Текст',
            'type' => 'text'
        ],
        [
            'label' => 'Input',
            'type' => 'input'
        ],
        [
            'label' => 'Textarea',
            'type' => 'editor'
        ],
        [
            'label' => 'Select',
            'type' => 'select'
        ],
        [
            'label' => 'Checkbox',
            'type' => 'checkbox'
        ],
        [
            'label' => 'Дата',
            'type' => 'date'
        ],
        [
            'label' => 'Час',
            'type' => 'time'
        ],
        [
            'label' => 'Приховане поле',
            'type' => 'hidden'
        ],
    ],

];
