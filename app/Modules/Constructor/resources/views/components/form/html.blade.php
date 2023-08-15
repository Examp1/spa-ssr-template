@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang,'btns_hide' => true])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-12 mb-3 input-group-sm">
                    <select name="{{ constructor_field_name($key, 'content.form') }}" class="form-control select2-form @error(constructor_field_name_dot($key, 'content.form')) is-invalid @enderror">
                        @foreach($params['forms']($lang) as $id => $name)
                            <option value="{{ $id }}" @if (isset($content['form']) && $content['form'] == $id) selected @endif>{{ $name }}</option>
                        @endforeach
                    </select>

                    @error(constructor_field_name_dot($key, 'content.form'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
