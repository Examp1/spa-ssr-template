<?php

/**
 | required fields:
 | route - route from section
 | permission_prefix - permission prefix from section
 */

return [
    'sections' => [
        'control' => [
            'title'         => 'Actions',
            'show'          => true,
            'collapse_hide' => false
        ],
        'status'  => [
            'title'         => 'Statuses',
            'show'          => true,
            'collapse_hide' => false,
            'statuses' => [
                [
                    'tooltip' => 'Publication status',
                    'title' => 'Status',
                    'field_name' => 'status',
                ]
            ]
        ],
        'info'    => [
            'title'         => 'Info',
            'show'          => true,
            'collapse_hide' => false,
            'blocks'        => [
                'order' => [
                    'title' => 'Sort order',
                    'field_name' => 'order',
                    'show'  => true
                ],
                'menu'  => [
                    'title' => 'Menu',
                    'field_name' => 'menu_id',
                    'show'  => false
                ],
                'parent_category'  => [
                    'title' => 'Parent category',
                    'field_name' => 'parent_id',
                    'show'  => false
                ],
                'main_category'  => [
                    'title' => 'Main category',
                    'field_name' => 'main_category_id',
                    'show'  => false
                ],
                'categories'  => [
                    'title' => 'Show in Categories',
                    'field_name' => 'categories[]',
                    'show'  => false
                ],
                'tags'  => [
                    'title' => 'Tags',
                    'field_name' => 'tags[]',
                    'show'  => false
                ],
                'public_date'  => [
                    'title' => 'Publication date',
                    'field_name' => 'public_date',
                    'show'  => false
                ],
            ]
        ],
        'video'   => [
            'title'         => 'Video',
            'show'          => false,
            'collapse_hide' => false
        ],
        'image'   => [
            'title'         => 'Image',
            'show'          => true,
            'collapse_hide' => false
        ],
        'delete'  => [
            'title'         => 'Removal',
            'btn_name'      => 'Remove',
            'show'          => true,
            'collapse_hide' => true
        ]
    ],
];
