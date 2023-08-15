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
                        <div data-item-id="#imageInputPlaceholder1" class="numbers-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                            <div class="col-12">
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][number]" placeholder="{{ $params['labels']['number'] }}" class="form-control mt-3 mb-1" disabled>
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" disabled>
                                <label>{{ $params['labels']['title_font_size'] }}</label>
                                <select class="form-control" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][title_font_size]">
                                    @foreach($params['font_size'] as $listKey => $litItem)
                                        <option value="{{ $listKey }}">{{ $litItem }}</option>
                                    @endforeach
                                </select>
                                <textarea class="form-control summernote_init" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][text]" disabled></textarea>
                                <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="numbers-list-container w-100">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                                <div class="col-12">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][number]" placeholder="{{ $params['labels']['number'] }}" class="form-control mt-3 mb-1" value="{{ $value['number'] ?? '' }}">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" value="{{ $value['title'] ?? '' }}">
                                    <label>{{ $params['labels']['title_font_size'] }}</label>
                                    <?php
                                    $font_size_val =  $value['title_font_size'] ?? '';
                                    ?>
                                    <select class="form-control" name="{ constructor_field_name($key, 'content.list') }}[{{ $k }}][title_font_size]">
                                        @foreach($params['font_size'] as $listKey => $litItem)
                                            <option value="{{ $listKey }}" @if($font_size_val == $listKey) selected @endif>{{ $litItem }}</option>
                                        @endforeach
                                    </select>
                                    <textarea class="form-control summernote_initialized" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][text]">{!! $value['text'] ?? '' !!}</textarea>
                                    <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-info btn-sm add-numbers-list-item_{{$lang}} d-block mt-2">Добавить</button>
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
                    <label>{{ $params['labels']['items_in_row'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.items_in_row')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.items_in_row') }}">
                        @foreach($params['items_in_row'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.items_in_row'), $content['items_in_row'] ?? '') == $listKey)) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'numbers','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
