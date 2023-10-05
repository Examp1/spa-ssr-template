<div class="col-md-3">
    <?php
    $statuses = [];
    foreach ($localizations as $key => $catLang) {
        $statuses[] = [
            'tooltip' => __('Language version status') . ' (' . config('translatable.locale_codes')[$key] . ')',
            'title' => __('Status') . ' ' . config('translatable.locale_codes')[$key],
            'field_name' => 'page_data[' . $key . '][status_lang]',
            'value' => $data[$key]['status_lang']??0,
        ];
    }

    //Preorder
    $statuses[] = [
            'tooltip' => __('On zero quantity show preorder form'),
            'title' => __('Preorder'),
            'field_name' => 'preorder',
        ];

    $statuses = array_merge(config('asider.sections.status.statuses'), $statuses);
    ?>

    @include('layouts.admin.components._asider', [
        'route' => 'products',
        'permission_prefix' => 'products',
        'sections' => [
            'info' => [
                'blocks' => [
                    'category_id' => [
                        'show' => true,
                        'field_name' => 'category_id',
                        'title' => 'Main category',
                    ],
                    'product_categories' => [
                        'show' => true,
                        'field_name' => 'categories[]',
                        'title' => 'Categories',
                    ],
                    'order' => [
                        'show' => true,
                        'default' => '25',
                    ],
                    'code' => [
                        'show' => true,
                        'field_name' => 'code',
                        'title' => 'Code',
                        'type' => 'text',
                    ],
                    'quantity' => [
                        'show' => true,
                        'field_name' => 'quantity',
                        'title' => 'Quantity',
                        'type' => 'number',
                    ],
                    'price' => [
                        'show' => true,
                        'field_name' => 'price',
                        'title' => 'Price',
                        'type' => 'number',
                    ],
                    'old_price' => [
                        'show' => true,
                        'field_name' => 'old_price',
                        'title' => 'Old price',
                        'type' => 'number',
                    ],
                    'cost' => [
                        'show' => true,
                        'field_name' => 'cost',
                        'title' => 'Cost',
                        'type' => 'number',
                    ],
                    'currency' => [
                        'show' => true,
                        'field_name' => 'currency',
                        'title' => 'Currency',
                        'type' => 'currency',
                    ],
                ],
            ],
            'status' => [
                'statuses' => $statuses,
            ],
            'delete' => [
                'btn_name' => 'Remove',
            ],
            'image' => [
                'show' => true,
            ],
        ],
    ])
</div>
