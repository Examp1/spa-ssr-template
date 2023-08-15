@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    <div class="row">
        <div class="col-12 input-group-sm mb-3">
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

    <div class="input-group">
        <div style="display: none;">
            <div data-item-id="#btnInputPlaceholder" class="full-image-btn-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center qwert">
                <div class="col-5">
                    <input type="text" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][text]" placeholder="Текст" class="form-control mt-3 mb-1 btn-preview-text" disabled>
                    <input type="text" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][link]" placeholder="Произвольная ссылка" class="form-control mt-3 mb-1 btn-preview-link" disabled>
                    <select class="form-control form-control mt-3 mb-1 btn-preview-type" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][type]">
                        @foreach($params['btn_type'] as $listKey => $listItem)
                            <option value="{{ $listKey }}">{{ $listItem }}</option>
                        @endforeach
                    </select>
                    <select class="form-control btn-icon-select2 btn-preview-icon" style="width: 150px" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][icon]">
                        @foreach($params['btn_icon'] as $listKey => $listItem)
                            <option value="{{ $listKey }}" data-icon="{{$listKey}}">{{ $listItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-5">
                    <div style="width: 100%" class="btn-preview-block">
                        <a href="javascript:void(0)" target="_blank"><span></span><i></i></a>
                    </div>
                </div>

                <div class="col-2">
                    <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="{{ constructor_field_name($key, 'content.btns') }}" value="">

        <div class="full-image-btn-list-container w-100">
            @foreach((array) old(constructor_field_name($key, 'content.btns'), $content['btns'] ?? []) as $k => $value)
                <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center qwert">
                    <div class="col-5">
                        <input type="text" name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][text]" placeholder="Текст" class="form-control mt-3 mb-1 btn-preview-text" value="{{ $value['text'] ?? '' }}">
                        <input type="text" name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][link]" placeholder="Произвольная ссылка" class="form-control mt-3 mb-1 btn-preview-link" value="{{ $value['link'] ?? '' }}">
                        <?php $type = $value['type'] ?? ''; ?>
                        <select class="form-control mt-3 mb-1 btn-preview-type" name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][type]">
                            @foreach($params['btn_type'] as $listKey => $listItem)
                                <option value="{{ $listKey }}" @if($type == $listKey) selected @endif>{{ $listItem }}</option>
                            @endforeach
                        </select>
                        <?php $icon = $value['icon'] ?? ''; ?>
                        <select class="form-control btn-icon-select2-ready btn-preview-icon" style="width: 150px" name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][icon]">
                            @foreach($params['btn_icon'] as $listKey => $listItem)
                                <option value="{{ $listKey }}" @if($icon == $listKey) selected @endif data-icon="{{$listKey}}">{{ $listItem }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-5">
                        <div style="width: 100%" class="btn-preview-block">
                            <a href="{{$value['link'] ?? 'javascript:void(0)'}}" target="_blank" class="style-btn {{$type}} @if($icon !== "non") hasIcon @endif">
                                <span>{{$value['text'] ?? ''}}</span>
                                <i class="@if($icon !== "non") linkIcon {{$icon}} @endif" @if($type === "text") style="display: none" @endif></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-2">
                        <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button type="button" class="btn btn-info btn-sm full-image-add-btn-list-item_{{$lang}} d-block mt-2">Добавить кнопку</button>
</div>

@include('constructor::layouts.footer')
