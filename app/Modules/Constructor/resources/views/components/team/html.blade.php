@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang,'btns_hide' => true])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-12">
                    <div class="form-group input-group-sm mb-12">
                        <input type="text" placeholder="{{ $params['labels']['title'] }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

                        @error(constructor_field_name_dot($key, 'content.title'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <div style="display: none;">
                        <div data-item-id="#imageInputPlaceholder1" class="team-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                            <div class="col-4">
                                {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][image]') }}
                            </div>

                            <div class="col-6">
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][name]" placeholder="{{ $params['labels']['name'] }}" class="form-control mt-3 mb-1" disabled>
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][position]" placeholder="{{ $params['labels']['position'] }}" class="form-control mt-3 mb-1" disabled>
                                <textarea class="form-control summernote_init" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][text]" disabled></textarea>
                            </div>

                            <div class="col-2">
                                <button type="button" class="btn btn-danger gallery3-remove-item_{{$lang}} float-right text-white">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="team-list-container w-100 gallery3-list-container-{{$lang}}">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">

                                <div class="col-4">
                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][image]', $value['image'] ?? null, $errors) }}
                                </div>

                                <div class="col-6">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][name]" placeholder="{{ $params['labels']['name'] }}" class="form-control mt-3 mb-1" value="{{ $value['name'] ?? '' }}">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][position]" placeholder="{{ $params['labels']['position'] }}" class="form-control mt-3 mb-1" value="{{ $value['position'] ?? '' }}">
                                    <textarea class="form-control summernote_initialized" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][text]">{!! $value['text'] ?? '' !!}</textarea>
                                </div>

                                <div class="col-2">
                                    <button type="button" class="btn btn-danger gallery3-remove-item_{{$lang}} text-white float-right">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-success text-white btn-sm add-team-list-item_{{$lang}} d-block mt-2">Додати</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
