@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-12">
                    <div class="form-group input-group-sm mb-12">
                        <label>{{ $params['labels']['title'] }}</label>
                        <input type="text" placeholder="{{ $params['labels']['title'] }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

                        @error(constructor_field_name_dot($key, 'content.title'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <textarea id="summernote_{{$lang}}_{{uniqid(time())}}" class="form-control summernote @error(constructor_field_name_dot($key, 'content.description')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.description') }}">{{ old(constructor_field_name_dot($key, 'content.description'), $content['description'] ?? '') }}</textarea>

                    @error(constructor_field_name_dot($key, 'content.description'))
                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <div style="display: none;">
                        <div data-item-id="#imageInputPlaceholder1" class="blocks-slider-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                            <div class="col-4">
                                {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][image]') }}
                            </div>
                            <div class="col-8">
                                <textarea class="form-control summernote_init" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][text]" disabled></textarea>
                                @include('admin.pieces.fields.interlink',[
                                    'lang' => $lang,
                                    'label' => 'Посилання',
                                    'name_type' => constructor_field_name($key, 'content.list') .'[#imageInputPlaceholder1][interlink_type]',
                                    'name_val' => constructor_field_name($key, 'content.list') .'[#imageInputPlaceholder1][interlink_val]',
                                ])
                                <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="blocks-slider-list-container w-100">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                                <div class="col-4">
                                    {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][image]', $value['image'] ?? null, $errors) }}
                                </div>
                                <div class="col-8">
                                    <textarea class="form-control summernote_initialized" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][text]">{!! $value['text'] ?? '' !!}</textarea>
                                    <?php
                                    $interlinkSelType = $value['interlink_type'] ?? '';
                                    $interlinkSelVal =  $value['interlink_val'] ?? '';
                                    ?>
                                    @include('admin.pieces.fields.interlink',[
                                        'lang' => $lang,
                                        'label' => 'Посилання',
                                        'name_type' => constructor_field_name($key, 'content.list'). '[' . $k . '][interlink_type]',
                                        'name_val' => constructor_field_name($key, 'content.list'). '[' . $k . '][interlink_val]',
                                        'sel_type' => $interlinkSelType,
                                        'sel_val' => $interlinkSelVal,
                                    ])
                                    <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-success text-white btn-sm add-blocks-slider-list-item_{{$lang}} d-block mt-2">Додати елемент</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'blocks-slider','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
