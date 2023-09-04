<input type="hidden" name="_tab" value="{{ $tab }}">

<ul class="nav nav-tabs" role="tablist">
    @foreach ($localizations as $key => $lang)
        <li class="nav-item">
            <a class="nav-link @if (config('translatable.locale') == $key) active @endif" data-toggle="tab"
                href="#main_lang_{{ $key }}" role="tab">
                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                        src="/images/langs/{{ $key }}.jpg" style="width: 20px" alt="{{ $key }}">
                    {{ $lang }}</span>
            </a>
        </li>
    @endforeach
</ul>

<br>

<div class="tab-content">
    @foreach ($localizations as $key => $catLang)
        <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
            id="main_lang_{{ $key }}" role="tabpanel">

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_app_name_{{ $key }}">Назва сайту</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][app_name]"
                        value="{{ old('setting_data.' . $key . '.app_name', $data[$key]['app_name'][0]['value'] ?? '') }}"
                        id="setting_app_name_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.app_name') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_agreement_{{ $key }}">Угода користувача</label>
                <div class="col-md-9">
                    <textarea
                        name="setting_data[{{ $key }}][agreement]"
                        id="setting_agreement_{{ $key }}"
                        class="editor form-control{{ $errors->has('setting_data.' . $key . '.agreement') ? ' is-invalid' : '' }}">
                        {{ old('setting_data.' . $key . '.agreement', $data[$key]['agreement'][0]['value'] ?? '') }}
                    </textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_copyright_{{ $key }}">{{ __('Copyright') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][copyright]" value="{{ old('setting_data.' . $key . '.copyright', $data[$key]['copyright'][0]['value'] ?? '') }}" id="setting_copyright_{{ $key }}" class="form-control{{ $errors->has('setting_data.' . $key . '.copyright') ? ' is-invalid' : '' }}">
                    <span class="small">{{ __('Copyright info') }}</span>
                </div>
            </div>

            <hr>
            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_cookie_title_{{ $key }}">{{ __('Cookie title') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][cookie_title]" value="{{ old('setting_data.' . $key . '.cookie_title', $data[$key]['cookie_title'][0]['value'] ?? '') }}" id="setting_cookie_title_{{ $key }}" class="form-control{{ $errors->has('setting_data.' . $key . '.cookie_title') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_cookie_text_{{ $key }}">{{ __('Cookie text') }}</label>
                <div class="col-md-9">
                    <textarea
                        name="setting_data[{{ $key }}][cookie_text]"
                        id="setting_cookie_text_{{ $key }}"
                        class="editor form-control{{ $errors->has('setting_data.' . $key . '.cookie_text') ? ' is-invalid' : '' }}">
                        {{ old('setting_data.' . $key . '.cookie_text', $data[$key]['cookie_text'][0]['value'] ?? '') }}
                    </textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_cookie_button_{{ $key }}">{{ __('Cookie button text') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][cookie_button]" value="{{ old('setting_data.' . $key . '.cookie_button', $data[$key]['cookie_button'][0]['value'] ?? '') }}" id="setting_cookie_button_{{ $key }}" class="form-control{{ $errors->has('setting_data.' . $key . '.cookie_button') ? ' is-invalid' : '' }}">
                </div>
            </div>
            <hr>

            {{-- <div class="form-group row">
                <label class="col-md-3 text-right"
                       for="setting_fast_form_{{ $key }}">Форма Замовлення(швидкого)</label>
                <div class="col-md-9">
                    <?php // $form_val = old('setting_data.' . $key . '.fast_form', $data[$key]['fast_form'][0]['value'] ?? '') ?>
                    <select name="setting_data[{{ $key }}][fast_form]" class="form-control">
                        <option value="">---</option>
                        @foreach(\App\Modules\Forms\Models\Form::query()->where('lang',$key)->get() as $item)
                            <option value="{{$item->id}}" @if($item->id == $form_val) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
        </div>
    @endforeach
</div>

<hr>

<?php
$defaultLang = config('translatable.locale');
$main_page_type_sel = old('setting_data.' . $defaultLang . '.main_page_type', $data[$defaultLang]['main_page_type'][0]['value'] ?? '');

$settings_fields = [
    'contact_page' => 'Сторінка контактів',
    // 'catalog_page' => 'Сторінка каталогу',
];

foreach ($settings_fields as $field => $text) {
    ${$field . "_sel"} = old('setting_data.' . $defaultLang . '.' . $field, $data[$defaultLang][$field][0]['value'] ?? '');
}
?>

<div class="form-group row">
    <label class="col-md-3 text-right">Ваша домашня сторінка це</label>
    <div class="col-md-9">
        <select name="setting_data[{{ $defaultLang }}][main_page_type]" class="form-control select-main_page_type"
            id="setting_main_page_type_{{ $defaultLang }}">
            <option value="">---</option>
            <option value="{{ \App\Models\Menu::TYPE_PAGE }}" @if ($main_page_type_sel == \App\Models\Menu::TYPE_PAGE) selected @endif>
                Сторінка</option>
{{--            <option value="{{ \App\Models\Menu::TYPE_LANDING }}" @if ($main_page_type_sel == \App\Models\Menu::TYPE_LANDING) selected @endif>--}}
{{--                Лендінг</option>--}}
        </select>
    </div>
</div>

<div class="form-group row main-box-page"
    @if ($main_page_type_sel == \App\Models\Menu::TYPE_PAGE) style="display: flex" @else style="display: none" @endif>
    <label class="col-md-3 text-right">{{ __('Choose') }}</label>
    <div class="col-md-9">
        <select name="setting_data[{{ $defaultLang }}][main_page_page_id]" class="form-control">
            @foreach (\App\Models\Pages::query()->active()->get() as $item)
                <option value="{{ $item->id }}" @if (old(
                        'setting_data.' . $defaultLang . '.main_page_page_id',
                        $data[$defaultLang]['main_page_page_id'][0]['value'] ?? '') == $item->id) selected @endif>
                    {{ $item->title }}</option>
            @endforeach
        </select>
    </div>
</div>

{{--<div class="form-group row main-box-landing"--}}
{{--    @if ($main_page_type_sel == \App\Models\Menu::TYPE_LANDING) style="display: flex" @else style="display: none" @endif>--}}
{{--    <label class="col-md-3 text-right">{{ __('Choose') }}</label>--}}
{{--    <div class="col-md-9">--}}
{{--        <select name="setting_data[{{ $defaultLang }}][main_page_landing_id]" class="form-control">--}}
{{--            @foreach (\App\Models\Landing::query()->active()->get() as $item)--}}
{{--                <option value="{{ $item->id }}" @if (old(--}}
{{--                        'setting_data.' . $defaultLang . '.main_page_landing_id',--}}
{{--                        $data[$defaultLang]['main_page_landing_id'][0]['value'] ?? '') == $item->id) selected @endif>--}}
{{--                    {{ $item->title }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}
{{--</div>--}}

<hr>

@foreach ($settings_fields as $field => $text)
    <div class="form-group row main-box-page" style="display: flex">
        <label class="col-md-3 text-right">{{ $text }}</label>
        <div class="col-md-9">
            <select name="setting_data[{{ $defaultLang }}][{{ $field }}_page_id]" class="form-control">
                @foreach(\App\Models\Pages::query()->active()->get() as $item)
                    <option value="{{$item->id}}" @if(old('setting_data.' . $defaultLang . '.' . $field . '_page_id', $data[$defaultLang][$field . '_page_id'][0]['value'] ?? '') == $item->id) selected @endif>{{$item->title}}</option>
                @endforeach
            </select>
        </div>
    </div>
@endforeach

<hr>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_favicon_{{ $defaultLang }}">Favicon</label>
    <div class="col-md-9">
        {{ media_preview_box('setting_data[' . $defaultLang . '][favicon]', old('setting_data.' . $defaultLang . '.favicon', $data[$defaultLang]['favicon'][0]['value'] ?? '')) }}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_default_og_image_{{ $defaultLang }}">Зображення для OpenGraph за
        замовчуванням</label>
    <div class="col-md-9">
        {{ media_preview_box('setting_data[' . $defaultLang . '][default_og_image]', old('setting_data.' . $defaultLang . '.default_og_image', $data[$defaultLang]['default_og_image'][0]['value'] ?? '')) }}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_logotype_{{ $defaultLang }}">Логотип</label>
    <div class="col-md-9">
        {{ media_preview_box('setting_data[' . $defaultLang . '][logotype]', old('setting_data.' . $defaultLang . '.logotype', $data[$defaultLang]['logotype'][0]['value'] ?? '')) }}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_logotype_admin_{{ $defaultLang }}">Логотип для адмін-панелі</label>
    <div class="col-md-9">
        {{ media_preview_box('setting_data[' . $defaultLang . '][logotype_admin]', old('setting_data.' . $defaultLang . '.logotype_admin', $data[$defaultLang]['logotype_admin'][0]['value'] ?? '')) }}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_logodark_{{ $defaultLang }}">Логотип Mobile</label>
    <div class="col-md-9">
        {{ media_preview_box("setting_data[".$defaultLang."][logodark]",old('setting_data.' . $defaultLang . '.logodark', $data[$defaultLang]['logodark'][0]['value'] ?? '')) }}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_logodark_{{ $defaultLang }}">Логотип Footer</label>
    <div class="col-md-9">
        {{ media_preview_box("setting_data[".$defaultLang."][logofooter]",old('setting_data.' . $defaultLang . '.logofooter', $data[$defaultLang]['logofooter'][0]['value'] ?? '')) }}
    </div>
</div>

<hr>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_head_code_{{ $defaultLang }}">Код в кінець HEAD</label>
    <div class="col-md-9">
        <textarea name="setting_data[{{ $defaultLang }}][head_code]" id="setting_head_code_{{ $defaultLang }}"
            cols="30" rows="10" class="form-control">{{ old('setting_data.' . $defaultLang . '.head_code', $data[$defaultLang]['head_code'][0]['value'] ?? '') }}</textarea>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_body_code_{{ $defaultLang }}">Код в кінець BODY</label>
    <div class="col-md-9">
        <textarea name="setting_data[{{ $defaultLang }}][body_code]" id="setting_body_code_{{ $defaultLang }}"
            cols="30" rows="10" class="form-control">{{ old('setting_data.' . $defaultLang . '.body_code', $data[$defaultLang]['body_code'][0]['value'] ?? '') }}</textarea>
    </div>
</div>

<hr>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_maps_api_key_{{ $defaultLang }}">Google Maps API-key</label>
    <div class="col-md-9">
        <input type="text" name="setting_data[{{ $defaultLang }}][maps_api_key]"
            value="{{ old('setting_data.' . $defaultLang . '.maps_api_key', $data[$defaultLang]['maps_api_key'][0]['value'] ?? '') }}"
            id="setting_maps_api_key_{{ $defaultLang }}"
            class="form-control{{ $errors->has('setting_data.' . $defaultLang . '.maps_api_key') ? ' is-invalid' : '' }}">
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".select-main_page_type").on('change', function() {
                let typePage = "{{ \App\Models\Menu::TYPE_PAGE }}";
                let typeLanding = "{{ \App\Models\Menu::TYPE_LANDING }}";

                if ($(this).val() == typePage) {
                    $(".main-box-page").show();
                    $(".main-box-landing").hide();
                } else if ($(this).val() == typeLanding) {
                    $(".main-box-landing").show();
                    $(".main-box-page").hide();
                } else {
                    $(".main-box-landing").hide();
                    $(".main-box-page").hide();
                }
            })
        });
    </script>
@endpush
