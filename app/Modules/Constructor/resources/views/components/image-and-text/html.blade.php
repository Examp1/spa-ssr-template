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
                <div class="col-4">
                    <div style="display: flex;gap: 15px">
                      <div>
                          <label>{{ $params['labels']['image'] }}</label>
                          {{ media_preview_box(constructor_field_name($key, 'content.image'), $content['image'] ?? null, $errors) }}
                      </div>
                        <div>
                            <label>{{ $params['labels']['image_mob'] }}</label>
                            {{ media_preview_box(constructor_field_name($key, 'content.image_mob'), $content['image_mob'] ?? null, $errors) }}
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <textarea
                        class="form-control summernote @error(constructor_field_name_dot($key, 'content.description')) is-invalid @enderror"
                        name="{{ constructor_field_name($key, 'content.description') }}">{{ old(constructor_field_name_dot($key, 'content.description'), $content['description'] ?? '') }}</textarea>

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
                <div class="col-3 mb-3">
                    <label>{{ $params['labels']['title_font_size'] }}</label>
                    <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.title_font_size')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title_font_size') }}">
                        @foreach($params['font_size'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if (old(constructor_field_name_dot($key, 'content.title_font_size'), $content['title_font_size'] ?? '') == $listKey) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 mb-3">
                    <label>{{ $params['labels']['image_position'] }}</label>
                    <select
                        class="form-control @error(constructor_field_name_dot($key, 'content.image_position')) is-invalid @enderror"
                        name="{{ constructor_field_name($key, 'content.image_position') }}">
                        @foreach($params['image_positions'] as $slug => $position)
                            <option value="{{ $slug }}"
                                    @if (old(constructor_field_name_dot($key, 'content.image_position'), $content['image_position'] ?? '') == $slug) selected @endif>{{ $position }}</option>
                        @endforeach
                    </select>
                    @error(constructor_field_name_dot($key, 'content.image_position'))
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-3 mb-3">
                    <label>{{ $params['labels']['column_width'] }}</label>
                    <select
                        class="form-control @error(constructor_field_name_dot($key, 'content.column_width')) is-invalid @enderror"
                        name="{{ constructor_field_name($key, 'content.column_width') }}">
                        @foreach($params['column_width'] as $slug2 => $position2)
                            <option value="{{ $slug2 }}"
                                    @if (old(constructor_field_name_dot($key, 'content.column_width'), $content['column_width'] ?? '') == $slug2) selected @endif>{{ $position2 }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 mb-3">
                    <label>{{ $params['labels']['image_height'] }}</label>
                    <select
                        class="form-control @error(constructor_field_name_dot($key, 'content.image_height')) is-invalid @enderror"
                        name="{{ constructor_field_name($key, 'content.image_height') }}">
                        @foreach($params['image_height'] as $slug3 => $position3)
                            <option value="{{ $slug3 }}"
                                    @if (old(constructor_field_name_dot($key, 'content.image_height'), $content['image_height'] ?? '') == $slug3) selected @endif>{{ $position3 }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 mb-3">
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
            @include('constructor::pieces.btns',['name_component'=>'image-and-text','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
