@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-6 mb-3 input-group-sm">
                    <label>{{ $params['labels']['title'] }}</label>
                    <input type="text" placeholder="{{ trans($params['labels']['title']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

                    @error(constructor_field_name_dot($key, 'content.title'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
                <div class="col-12">
                    <textarea id="summernote_{{$lang}}_{{uniqid(time())}}" class="form-control summernote @error(constructor_field_name_dot($key, 'content.text')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.text') }}">{{ old(constructor_field_name_dot($key, 'content.text'), $content['text'] ?? '') }}</textarea>

                    @error(constructor_field_name_dot($key, 'content.text'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <div style="display: none;">
                        <div data-item-id="#imageInputPlaceholder1" class="list-stages-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                            <div class="col-10">
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" disabled>
                                <textarea class="summernote_init" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][text]" cols="30" rows="10" disabled></textarea>
                            </div>

                            <div class="col-2">
                                <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="list-stages-container w-100">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                                <div class="col-10">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" value="{{ $value['title'] ?? '' }}">
                                    <textarea class="form-control summernote_initialized" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][text]">{!! $value['text'] ?? '' !!}</textarea>
                                </div>

                                <div class="col-1">
                                    <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-info btn-sm add-list-stages-item_{{$lang}} d-block mt-2">Додати елемент</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            <div class="row">
                <div class="col-4 input-group-sm mb-3">
                    <label>{{ $params['labels']['title_font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.title_font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title_font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.title_font_size'), $content['title_font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 input-group-sm mb-3">
                    <label>{{ $params['labels']['content_position'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.content_position')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.content_position') }}">
                        @foreach($params['content_position'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.content_position'), $content['content_position'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4 input-group-sm mb-3">
                    <label>{{ $params['labels']['font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.font_size'), $content['font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'stages','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
