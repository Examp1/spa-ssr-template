@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-12 mb-3 input-group-sm">
                    <label>{{ $params['labels']['title'] }}</label>
                    <input type="text" placeholder="{{ trans($params['labels']['title']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

                    @error(constructor_field_name_dot($key, 'content.title'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <div style="display: none;">
                        <div id="#imageInputPlaceholder1" style="background-color: white" data-item-id="#imageInputPlaceholder1" class="gallery-list-template item-group m-1 border border-grey-light p-1 align-items-center d-inline-flex ui-state-item-no-select">
                            <div class="col-12">
                                {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][image]') }}

                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][image_title]" placeholder="{{ $params['labels']['image_title'] }}" class="form-control mt-3 mb-1" disabled>
                                <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][sort]" class="sort_sort">
                                {{-- <textarea class="form-control" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][image_description]" disabled placeholder="{{ $params['labels']['image_description'] }}"></textarea> --}}
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

                    <div class="gallery-list-container w-100 gallery_sortable_{{$lang}}">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div id="{{uniqid()}}_{{ $k }}" style="background-color: white" data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 align-items-center d-inline-flex ui-state-item-no-select">
                                <div class="col-12">
                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][image]', $value['image'] ?? null, $errors) }}

                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][image_title]" placeholder="{{ $params['labels']['image_title'] }}" class="form-control mt-3 mb-1" value="{{ $value['image_title'] ?? '' }}">
                                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][sort]" class="sort_sort" value="{{ $value['sort'] ?? '' }}">
                                    {{-- <textarea class="form-control" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][image_description]">{!! $value['image_description'] ?? '' !!}</textarea> --}}
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

                <button type="button" class="btn btn-info btn-sm add-gallery-list-item_{{$lang}} d-block mt-2">Добавить картинку</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            <div class="row">
                <div class="col-3 input-group-sm mb-3">
                    <label>{{ $params['labels']['title_font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.title_font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title_font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.title_font_size'), $content['title_font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 input-group-sm mb-3">
                    <label>{{ $params['labels']['size_fix'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.size_fix')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.size_fix') }}">
                        @foreach($params['size_fix'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.size_fix'), $content['size_fix'] ?? '') == $listKey)) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 input-group-sm mb-3">
                    <label>{{ $params['labels']['align'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.align')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.align') }}">
                        @foreach($params['align'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.align'), $content['align'] ?? '') == $listKey)) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 input-group-sm mb-3">
                    <label>{{ $params['labels']['show_btns'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.show_btns')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.show_btns') }}">
                        @foreach($params['show_btns'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.show_btns'), $content['show_btns'] ?? '') == $listKey)) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'gallery','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
