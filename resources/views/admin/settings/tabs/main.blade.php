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

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_index_{{ $key }}">{{ __('NO INDEX') }}</label>
                <div class="col-md-9">
                    <?php $no_index = old('setting_data.' . $key . '.no_index', $data[$key]['no_index'][0]['value'] ?? ''); ?>
                    <input type="hidden" name="setting_data[{{ $key }}][no_index]" value="0">
                    <input type="checkbox" value="1" @if($no_index) checked @endif name="setting_data[{{ $key }}][no_index]" id="setting_no_index_{{ $key }}" class="{{ $errors->has('setting_data.' . $key . '.no_index') ? ' is-invalid' : '' }}">
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

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_cookie_show_{{ $key }}">{{ __('Cookie show site') }}</label>
                <div class="col-md-9">
                    <?php $cookie_show_checked = old('setting_data.' . $key . '.cookie_show', $data[$key]['cookie_show'][0]['value'] ?? ''); ?>
                    <input type="hidden" name="setting_data[{{ $key }}][cookie_show]" value="0">
                    <input type="checkbox" value="1" @if($cookie_show_checked) checked @endif name="setting_data[{{ $key }}][cookie_show]" id="setting_cookie_show_{{ $key }}" class="{{ $errors->has('setting_data.' . $key . '.cookie_show') ? ' is-invalid' : '' }}">
                </div>
            </div>
            <hr>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                       for="setting_fast_form_{{ $key }}">Форма швидкого Замовлення</label>
                <div class="col-md-9">
                    <?php $form_val = old('setting_data.' . $key . '.fast_form', $data[$key]['fast_form'][0]['value'] ?? '') ?>
                    <select name="setting_data[{{ $key }}][fast_form]" class="form-control">
                        <option value="">---</option>
                        @foreach(\App\Modules\Forms\Models\Form::query()->where('lang',$key)->get() as $item)
                            <option value="{{$item->id}}" @if($item->id == $form_val) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                       for="setting_preorder_form_{{ $key }}">Форма для товарів "Під амовлення"</label>
                <div class="col-md-9">
                    <?php $form_val = old('setting_data.' . $key . '.preorder_form', $data[$key]['preorder_form'][0]['value'] ?? '') ?>
                    <select name="setting_data[{{ $key }}][preorder_form]" class="form-control">
                        <option value="">---</option>
                        @foreach(\App\Modules\Forms\Models\Form::query()->where('lang',$key)->get() as $item)
                            <option value="{{$item->id}}" @if($item->id == $form_val) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                       for="setting_return_form_{{ $key }}">Форма "Обмін та повернення"</label>
                <div class="col-md-9">
                    <?php $form_val = old('setting_data.' . $key . '.return_form', $data[$key]['return_form'][0]['value'] ?? '') ?>
                    <select name="setting_data[{{ $key }}][return_form]" class="form-control">
                        <option value="">---</option>
                        @foreach(\App\Modules\Forms\Models\Form::query()->where('lang',$key)->get() as $item)
                            <option value="{{$item->id}}" @if($item->id == $form_val) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>
            <h4>Промо бар</h4>

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_promobar_text_{{ $key }}">{{ __('Text') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][promobar_text]" value="{{ old('setting_data.' . $key . '.promobar_text', $data[$key]['promobar_text'][0]['value'] ?? '') }}" id="setting_promobar_text_{{ $key }}" class="form-control{{ $errors->has('setting_data.' . $key . '.promobar_text') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_promobar_button_name_{{ $key }}">{{ __('Title on button') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][button_name]" value="{{ old('setting_data.' . $key . '.button_name', $data[$key]['button_name'][0]['value'] ?? '') }}" id="setting_button_name_{{ $key }}" class="form-control{{ $errors->has('setting_data.' . $key . '.button_name') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right" for="setting_button_link_{{ $key }}">{{ __('Link') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][button_link]" value="{{ old('setting_data.' . $key . '.button_link', $data[$key]['button_link'][0]['value'] ?? '') }}" id="setting_button_link_{{ $key }}" class="form-control{{ $errors->has('setting_data.' . $key . '.button_link') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <hr>

            @php
                $data[$key]['search_suggestions'] = isset($data[$key]['search_suggestions']) ? json_decode($data[$key]['search_suggestions'][0]['value'], true) : [];
            @endphp
            <div class="form-group row">
                <label class="col-md-3 text-right">Часто шукають</label>
                <div class="col-md-9">
                    <div class="input-group mb-1">
                        <div style="display: none;">
                            <div data-item-id="#dynamicListPlaceholder" class="item-search_suggestions-template-none-{{ $key }} item-group input-group mb-1">
                                <input type="text"
                                    placeholder="Текст"
                                    name="setting_data[{{ $key }}][search_suggestions][#dynamicListPlaceholder][text]"
                                    class="form-control mr-1"
                                    disabled=""
                                >
                                <input type="text"
                                    placeholder="Ланка"
                                    name="setting_data[{{ $key }}][search_suggestions][#dynamicListPlaceholder][url]"
                                    class="form-control mr-1"
                                    disabled=""
                                >
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger remove-item text-white">{{ __('Remove') }}</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="setting_data[{{ $key }}][search_suggestions]" value="">

                        <div class="items-search_suggestions-container-{{ $key }} w-100">
                            @if (!empty($data[$key]['search_suggestions']))
                                @foreach($data[$key]['search_suggestions'] as $k => $value)
                                    <div data-item-id="{{$k}}" class="item-template item-group input-group mb-1">
                                        <input type="text"
                                            placeholder="Текст"
                                            name="setting_data[{{ $key }}][search_suggestions][{{ $k }}][text]"
                                            class="form-control mr-1"
                                            value="{{ $value['text'] }}"
                                        >
                                        <input type="text"
                                            placeholder="Ланка"
                                            name="setting_data[{{ $key }}][search_suggestions][{{ $k }}][url]"
                                            class="form-control mr-1"
                                            value="{{ $value['url'] }}"
                                        >
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-danger remove-item text-white">{{ __('Remove') }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>

                    <button type="button"
                            class="btn btn-info btn-sm add-item-array-field"
                            data-field_name="search_suggestions"
                            data-lang="{{ $key }}"
                    >{{ __('Add') }}</button>
                </div>
            </div>
        </div>
    @endforeach
</div>

<hr>

<?php
$defaultLang = config('translatable.locale');
$main_page_type_sel = old('setting_data.' . $defaultLang . '.main_page_type', $data[$defaultLang]['main_page_type'][0]['value'] ?? '');

// Pages types
$settings_fields = [
    'contact_page' => 'Сторінка контактів',
    'catalog_page' => 'Сторінка каталогу',
    'about_page'   => 'Сторінка Про нас',
];

// Menu types
$menu_fields = [
    'main' => 'Головне меню',
    'footer' => 'Меню в футері',
    'sidebar'   => 'Додаткове меню',
];
?>

<div class="form-group row">
    <label class="col-md-3 text-right">Ваша домашня сторінка це</label>
    <div class="col-md-9">
        <select name="setting_data[{{ $defaultLang }}][main_page_type]" class="form-control select-main_page_type"
            id="setting_main_page_type_{{ $defaultLang }}">
            <option value="">---</option>
            <option value="{{ \App\Models\Menu::TYPE_PAGE }}" @if ($main_page_type_sel == \App\Models\Menu::TYPE_PAGE) selected @endif>
                Сторінка</option>
            <option value="{{ \App\Models\Menu::TYPE_LANDING }}" @if ($main_page_type_sel == \App\Models\Menu::TYPE_LANDING) selected @endif>
                Лендінг</option>
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

<div class="form-group row main-box-landing"
    @if ($main_page_type_sel == \App\Models\Menu::TYPE_LANDING) style="display: flex" @else style="display: none" @endif>
    <label class="col-md-3 text-right">{{ __('Choose') }}</label>
    <div class="col-md-9">
        <select name="setting_data[{{ $defaultLang }}][main_page_landing_id]" class="form-control">
            @foreach (\App\Models\Landing::query()->active()->get() as $item)
                <option value="{{ $item->id }}" @if (old(
                        'setting_data.' . $defaultLang . '.main_page_landing_id',
                        $data[$defaultLang]['main_page_landing_id'][0]['value'] ?? '') == $item->id) selected @endif>
                    {{ $item->title }}</option>
            @endforeach
        </select>
    </div>
</div>

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

@foreach ($menu_fields as $field => $text)
    <div class="form-group row main-box-page" style="display: flex">
        <label class="col-md-3 text-right">{{ $text }}</label>
        <div class="col-md-9">
            <select name="setting_data[{{ $defaultLang }}][{{ $field }}_menu_id]" class="form-control">

                @foreach(\App\Models\Menu::getTags() as $id => $item)
                    <option value="{{$id}}" @if(old('setting_data.' . $defaultLang . '.' . $field . '_menu_id', $data[$defaultLang][$field . '_menu_id'][0]['value'] ?? '') == $id) selected @endif>{{$item}}</option>
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

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_logo_for_pdf_{{ $defaultLang }}">Логотип для pdf</label>
    <div class="col-md-9">
        {{ media_preview_box("setting_data[".$defaultLang."][logo_for_pdf]",old('setting_data.' . $defaultLang . '.logo_for_pdf', $data[$defaultLang]['logo_for_pdf'][0]['value'] ?? '')) }}
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

            $(document).on('click', '.add-item-array-field', function() {
                let field_name = $(this).data('field_name');
                let lang = $(this).data('lang');

                const template = $(this).parent().find('.item-' + field_name + '-template-none-' + lang);
                const container = $(this).parent().find('.items-' + field_name + '-container-' + lang);

                create_item(template, container, '#dynamicListPlaceholder');

                container.find('input, textarea').prop('disabled', false);
            });

            $(document).on('click', '.remove-item', function() {
                $(this).parents('.item-group').remove();
            });
        });
    </script>
@endpush
