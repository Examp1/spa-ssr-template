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
                <div class="d-flex col-12">
                    <div>
                        {{ media_preview_box(constructor_field_name($key, 'content.image'), $content['image'] ?? null, $errors) }}
                    </div>
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
                    <label>{{ $params['labels']['width_type'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.width_type')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.width_type') }}">
                        @foreach($params['width_type'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.width_type'), $content['width_type'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'full-image','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
