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
                <div class="col-12 mb-3 input-group-sm">
                    <textarea id="summernote_{{$lang}}_{{uniqid(time())}}" class="form-control summernote @error(constructor_field_name_dot($key, 'content.text')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.text') }}">{{ old(constructor_field_name_dot($key, 'content.description'), $content['text'] ?? '') }}</textarea>

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
                    <label>{{ $params['labels']['fill'] }}</label>
                    <?php $fill_val = old(constructor_field_name_dot($key, 'content.fill'), $content['fill'] ?? ''); ?>
                    <select class="form-control fill-select @error(constructor_field_name_dot($key, 'content.fill')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.fill') }}">
                        @foreach($params['fill'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if ($fill_val == $listKey)) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 input-group-sm mb-3 bg_color-block" @if($fill_val == "1") style="display: none" @endif>
                    <label>{{ $params['labels']['bg_color'] }}</label>
                    <input type="color" class="form-control" name="{{ constructor_field_name($key, 'content.bg_color') }}" value="{{ old(constructor_field_name_dot($key, 'content.bg_color'), $content['bg_color'] ?? '') }}">
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'cta','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
