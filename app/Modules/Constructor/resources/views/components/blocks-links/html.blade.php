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
                        <div data-item-id="#imageInputPlaceholder1" class="blocks-links-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                            <div class="col-12">
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" disabled>
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][subtitle]" placeholder="{{ $params['labels']['subtitle'] }}" class="form-control mt-3 mb-1" disabled>

                                <div class="simple-link-blocks">
                                    <span class="btn btn-success text-white add-links_{{$lang}}">Додати посилання</span>
                                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][count_link]" disabled>
                                    <div class="link-blocks" style="margin-top: 20px">
                                        <div style="display: none;margin: 15px 0" class="link-b link-b-1">
                                            <div class="row">
                                                <div class="col-4">
                                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][link_blocks_image_1]',null, $errors) }}
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][link_blocks_link_1]" placeholder="Посилання" class="form-control mt-3 mb-1" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: none;margin: 15px 0" class="link-b link-b-2">
                                            <div class="row">
                                                <div class="col-4">
                                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][link_blocks_image_2]',null, $errors) }}
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][link_blocks_link_2]" placeholder="Посилання" class="form-control mt-3 mb-1" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: none;margin: 15px 0" class="link-b link-b-3">
                                            <div class="row">
                                                <div class="col-4">
                                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][link_blocks_image_3]',null, $errors) }}
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][link_blocks_link_3]" placeholder="Посилання" class="form-control mt-3 mb-1" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: none;margin: 15px 0" class="link-b link-b-4">
                                            <div class="row">
                                                <div class="col-4">
                                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][link_blocks_image_4]',null, $errors) }}
                                                </div>
                                                <div class="col-8">
                                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][link_blocks_link_4]" placeholder="Посилання" class="form-control mt-3 mb-1" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="blocks-links-list-container w-100">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                                <div class="col-12">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" value="{{ $value['title'] ?? '' }}">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][subtitle]" placeholder="{{ $params['labels']['subtitle'] }}" class="form-control mt-3 mb-1" value="{{ $value['subtitle'] ?? '' }}">

                                    <div class="simple-link-blocks">
                                        <span class="btn btn-success text-white add-links_{{$lang}}">Додати посилання</span>
                                        <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][count_link]" value="{{ $value['count_link'] ?? '' }}">
                                        <div class="link-blocks">
                                            <div style="@if($value['count_link'] > 0) display: block @else display: none @endif; margin: 15px 0" class="link-b link-b-1">
                                                <div class="row">
                                                    <div class="col-4">
                                                        {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][link_blocks_image_1]', $value['link_blocks_image_1'] ?? null, $errors) }}
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][link_blocks_link_1]" placeholder="Посилання" class="form-control mt-3 mb-1" value="{{ $value['link_blocks_link_1'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="@if($value['count_link'] > 1) display: block @else display: none @endif; margin: 15px 0" class="link-b link-b-2">
                                                <div class="row">
                                                    <div class="col-4">
                                                        {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][link_blocks_image_2]', $value['link_blocks_image_2'] ?? null, $errors) }}
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][link_blocks_link_2]" placeholder="Посилання" class="form-control mt-3 mb-1" value="{{ $value['link_blocks_link_2'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="@if($value['count_link'] > 2) display: block @else display: none @endif; margin: 15px 0" class="link-b link-b-3">
                                                <div class="row">
                                                    <div class="col-4">
                                                        {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][link_blocks_image_3]', $value['link_blocks_image_3'] ?? null, $errors) }}
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][link_blocks_link_3]" placeholder="Посилання" class="form-control mt-3 mb-1" value="{{ $value['link_blocks_link_3'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="@if($value['count_link'] > 3) display: block @else display: none @endif; margin: 15px 0" class="link-b link-b-4">
                                                <div class="row">
                                                    <div class="col-4">
                                                        {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][link_blocks_image_4]', $value['link_blocks_image_4'] ?? null, $errors) }}
                                                    </div>
                                                    <div class="col-8">
                                                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][link_blocks_link_4]" placeholder="Посилання" class="form-control mt-3 mb-1" value="{{ $value['link_blocks_link_4'] ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-info btn-sm add-blocks-links-list-item_{{$lang}} d-block mt-2">Додати елемент</button>
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
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'blocks-links','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
