@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="input-group">
                    <div style="display: none;">
                        <div id="#imageInputPlaceholder1" style="background-color: white" data-item-id="#imageInputPlaceholder1" class="partners-list-template item-group m-1 border border-grey-light p-1 align-items-center d-inline-flex ui-state-item-no-select">
                            <div class="col-12">
                                {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][image]') }}
                                <br>
                                <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][sort]" class="sort_sort">
                                @include('admin.pieces.fields.interlink',[
                                    'lang' => $lang,
                                    'label' => 'Посилання',
                                    'name_type' => constructor_field_name($key, 'content.list') .'[#imageInputPlaceholder1][interlink_type]',
                                    'name_val' => constructor_field_name($key, 'content.list') .'[#imageInputPlaceholder1][interlink_val]',
                                ])
                                <button type="button" class="btn btn-danger remove-item text-white d-block mt-2">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="partners-list-container w-100 partners_sortable_{{$lang}}">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div id="{{uniqid()}}_{{ $k }}" style="background-color: white" data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 align-items-center d-inline-flex ui-state-item-no-select">
                                <div class="col-12">
                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][image]', $value['image'] ?? null, $errors) }}
                                    <br>
                                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][sort]" class="sort_sort" value="{{ $value['sort'] ?? '' }}">
                                    <?php
                                    $interlinkSelType = $value['interlink_type'] ?? '';
                                    $interlinkSelVal =  $value['interlink_val'] ?? '';
                                    ?>
                                    @include('admin.pieces.fields.interlink',[
                                        'lang' => $lang,
                                        'label' => 'Посилання',
                                        'name_type' => constructor_field_name($key, 'content.list'). '[' . $k . '][interlink_type]',
                                        'name_val' => constructor_field_name($key, 'content.list'). '[' . $k . '][interlink_val]',
                                        'sel_type' => $interlinkSelType,
                                        'sel_val' => $interlinkSelVal,
                                    ])
                                    <button type="button" class="btn btn-danger remove-item text-white d-block mt-2">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-info btn-sm add-partners-list-item_{{$lang}} d-block mt-2">Додати партнера</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'partners','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
