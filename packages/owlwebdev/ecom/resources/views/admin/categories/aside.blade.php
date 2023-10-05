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

    $statuses = array_merge(config('asider.sections.status.statuses'), $statuses);
    ?>

    @include('layouts.admin.components._asider', [
        'route' => 'categories',
        'permission_prefix' => 'categories',
        'sections' => [
            'info' => [
                'blocks' => [
                    'parent_id' => [
                        'show' => true,
                        'field_name' => 'parent_id',
                        'title' => 'Parent category',
                    ],
                    'attribute_group_id' => [
                        'show' => true,
                        'field_name' => 'attribute_group_id',
                        'title' => 'Attributes group',
                    ],
                    'size_grid_id' => [
                        'show' => true,
                        'field_name' => 'size_grid_id',
                        'title' => 'Size grid',
                    ],
                    'order' => [
                        'show' => true,
                        'default' => '25',
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
            // 'ban_image' => [
            //     'show' => true,
            // ],
        ],
    ])
</div>
