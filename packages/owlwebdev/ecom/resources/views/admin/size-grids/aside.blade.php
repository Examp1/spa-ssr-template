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
    $front_link_enabled = false;
    ?>

    @include('layouts.admin.components._asider', [
        'route' => 'size-grid',
        'permission_prefix' => 'size_grids',
        'sections' => [
            'info' => [
                'show' => false,
            ],
            'status' => [
                'statuses' => $statuses,
            ],
            'delete' => [
                'btn_name' => 'Remove',
            ],
            'image' => [
                'show' => false,
            ]
        ]
    ])
</div>
