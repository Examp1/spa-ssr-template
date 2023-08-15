@include('constructor::layouts.header',['lang' => $lang])
@php($isNew = count($content) ? false : true)

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
                        <div data-item-id="#imageInputPlaceholder1" class="blocks-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                            <div class="col-4">
                                {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][image]') }}
                            </div>
                            <div class="col-8">
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" disabled>
                                <textarea class="form-control summernote_init" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][text]" disabled></textarea>
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][btn_name]" placeholder="{{ $params['labels']['btn_name'] }}" class="form-control mt-3 mb-1" disabled>
                                @include('admin.pieces.fields.interlink',[
                                    'lang' => $lang,
                                    'label' => 'Посилання',
                                    'name_type' => constructor_field_name($key, 'content.list') .'[#imageInputPlaceholder1][interlink_type]',
                                    'name_val' => constructor_field_name($key, 'content.list') .'[#imageInputPlaceholder1][interlink_val]',
                                ])
                                <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="blocks-list-container w-100">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                                <div class="col-4">
                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][image]', $value['image'] ?? null, $errors) }}
                                </div>
                                <div class="col-8">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" value="{{ $value['title'] ?? '' }}">
                                    <textarea class="form-control summernote_initialized" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][text]">{!! $value['text'] ?? '' !!}</textarea>
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][btn_name]" placeholder="{{ $params['labels']['btn_name'] }}" class="form-control mt-3 mb-1" value="{{ $value['btn_name'] ?? '' }}">
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
                                    <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-info btn-sm add-blocks-list-item_{{$lang}} d-block mt-2">Додати елемент</button>
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
                    <label>В рядку</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.title_column_select')) is-invalid @enderror item-row-template-count" name="{{ constructor_field_name($key, 'content.title_column_select') }}">
                        @foreach($params['columns'] as $slug => $position)
                            <option value="{{ $slug }}" @if (old(constructor_field_name_dot($key, 'content.title_column_select'), $content['title_column_select'] ?? '') == $slug) selected @endif>{{ $position }}</option>
                        @endforeach
                    </select>

                    @error(constructor_field_name_dot($key, 'content.title_column_select'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h4> <label>{{$params['labels']['card_btn_style_title']}}</label></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-3 input-group-sm mb-3">
                    <select class="form-control form-control mt-3 mb-1 btn-preview-type" style="margin-top: 0!important;" name="{{ constructor_field_name($key, 'content.card_btn_style_type') }}">
                        @foreach($params['btn_type'] as $listKey => $listItem)
                            <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.card_btn_style_type'), $content['card_btn_style_type'] ?? '') == $listKey) || ($isNew && $listKey === 'simple')) selected @endif>{{ $listItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 input-group-sm mb-3">
                    <select class="form-control btn-icon-select2 btn-icon-select2-ready2" style="width: 150px" name="{{ constructor_field_name($key, 'content.card_btn_style_icon') }}">
                        @foreach($params['btn_icon'] as $listKey => $listItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.card_btn_style_icon'), $content['card_btn_style_icon'] ?? '') == $listKey) selected @endif data-icon="{{$listKey}}">{{ $listItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'blocks','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')

