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

                <div class="col-6 mb-3 input-group-sm">
                    <label>{{ $params['labels']['subtitle'] }}</label>
                    <input type="text" placeholder="{{ trans($params['labels']['subtitle']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.subtitle')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.subtitle') }}" value="{{ old(constructor_field_name_dot($key, 'content.subtitle'), $content['subtitle'] ?? '') }}">

                    @error(constructor_field_name_dot($key, 'content.subtitle'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <textarea id="summernote_{{$lang}}_{{uniqid(time())}}" class="form-control summernote @error(constructor_field_name_dot($key, 'content.description')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.description') }}">{{ old(constructor_field_name_dot($key, 'content.description'), $content['description'] ?? '') }}</textarea>

                    @error(constructor_field_name_dot($key, 'content.description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
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
                    <label>{{ $params['labels']['text_width'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.text_width')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.text_width') }}">
                        @foreach($params['text_width'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.text_width'), $content['text_width'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 input-group-sm mb-3">
                    <label>{{ $params['labels']['text_align'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.text_align')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.text_align') }}">
                        @foreach($params['text_align'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.text_align'), $content['text_align'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 input-group-sm mb-3">
                    <label>{{ $params['labels']['font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.font_size'), $content['font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <label>{{ trans($params['labels']['fon_image']) }}</label>
                    {{ media_preview_box(constructor_field_name($key, 'content.fon_image'), $content['fon_image'] ?? null, $errors) }}
                    <br>
                </div>
            </div>

            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'simple-text','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
