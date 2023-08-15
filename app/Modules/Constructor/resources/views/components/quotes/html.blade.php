@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang,'btns_hide' => true])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-12 mb-3 input-group-sm">
                    <input type="text" placeholder="{{ trans($params['labels']['author']) }}" class="form-control @error(constructor_field_name_dot($key, 'content.author')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.author') }}" value="{{ old(constructor_field_name_dot($key, 'content.author'), $content['author'] ?? '') }}">

                    @error(constructor_field_name_dot($key, 'content.author'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>

                <div class="col-12">
                    <textarea class="form-control summernote @error(constructor_field_name_dot($key, 'content.text')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.text') }}">{{ old(constructor_field_name_dot($key, 'content.text'), $content['text'] ?? '') }}</textarea>

                    @error(constructor_field_name_dot($key, 'content.text'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
