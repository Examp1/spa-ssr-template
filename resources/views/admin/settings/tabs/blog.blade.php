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
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_title_{{ $key }}">{{ __('Title') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][{{ $tab }}_title]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_title', $data[$key][$tab . '_title'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_title_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_title') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_description_{{ $key }}">{{ __('Description') }}</label>
                <div class="col-md-9">
                    <textarea class="form-control summernote editor"
                        name="setting_data[{{ $key }}][{{ $tab }}_description]"
                        id="setting_{{ $tab }}_description_{{ $key }}" cols="30" rows="10">{{ old('setting_data.' . $key . '.' . $tab . '_description', $data[$key][$tab . '_description'][0]['value'] ?? '') }}</textarea>
                </div>
            </div>

            <hr>
            <h4>SEO</h4>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_meta_title_{{ $key }}">{{ __('Title') }}</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][{{ $tab }}_meta_title]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_meta_title', $data[$key][$tab . '_meta_title'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_meta_title_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_meta_title') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_meta_description_{{ $key }}">{{ __('Description') }}</label>
                <div class="col-md-9">
                    <input type="text"
                        name="setting_data[{{ $key }}][{{ $tab }}_meta_description]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_meta_description', $data[$key][$tab . '_meta_description'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_meta_description_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_meta_description') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <hr>
            <h4>{{ __('Subscriptions') }}</h4>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_subscribe_block_visible_{{ $key }}">Відображення блоку підписки</label>
                <div class="col-md-9">
                    <?php $subscribeBlockVisibleVal = old('setting_data.' . $key . '.' . $tab . '_subscribe_block_visible', $data[$key][$tab . '_subscribe_block_visible'][0]['value'] ?? ''); ?>
                    <select name="setting_data[{{ $key }}][{{ $tab }}_subscribe_block_visible]"
                        id="setting_{{ $tab }}_subscribe_block_visible_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_subscribe_block_visible') ? ' is-invalid' : '' }}"
                        style="width: 60px">
                        <option value="1" @if ($subscribeBlockVisibleVal == 1) selected @endif>{{ __('Yes') }}</option>
                        <option value="0" @if ($subscribeBlockVisibleVal == 0) selected @endif>{{ __('No') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_subscribe_title_{{ $key }}">Заголовок блоку</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][{{ $tab }}_subscribe_title]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_subscribe_title', $data[$key][$tab . '_subscribe_title'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_subscribe_title_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_subscribe_title') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_subscribe_placeholder_{{ $key }}">Підказка на поле</label>
                <div class="col-md-9">
                    <input type="text"
                        name="setting_data[{{ $key }}][{{ $tab }}_subscribe_placeholder]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_subscribe_placeholder', $data[$key][$tab . '_subscribe_placeholder'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_subscribe_placeholder_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_subscribe_placeholder') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_subscribe_block_btn_type_{{ $key }}">Вигляд кнопки</label>
                <div class="col-md-9">
                    <?php $subscribeBlockBtnTypeVal = old('setting_data.' . $key . '.' . $tab . '_subscribe_block_btn_type', $data[$key][$tab . '_subscribe_block_btn_type'][0]['value'] ?? ''); ?>
                    <select name="setting_data[{{ $key }}][{{ $tab }}_subscribe_block_btn_type]"
                        id="setting_{{ $tab }}_subscribe_block_btn_type_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_subscribe_block_btn_type') ? ' is-invalid' : '' }}"
                        style="width: 80px">
                        <option value="text" @if ($subscribeBlockBtnTypeVal == 'text') selected @endif>Текст</option>
                        <option value="icon" @if ($subscribeBlockBtnTypeVal == 'icon') selected @endif>Іконка</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_subscribe_btn_title{{ $key }}">Напис на кнопці</label>
                <div class="col-md-9">
                    <input type="text"
                        name="setting_data[{{ $key }}][{{ $tab }}_subscribe_btn_title]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_subscribe_btn_title', $data[$key][$tab . '_subscribe_btn_title'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_subscribe_btn_title_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_subscribe_btn_title') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right">Іконка кнопки</label>
                <div class="col-md-9">
                    <?php
                    $subscribeBtnIconVal = old('setting_data.' . $key . '.' . $tab . '_subscribe_btn_icon', $data[$key][$tab . '_subscribe_btn_icon'][0]['value'] ?? '');
                    $iconsList = config('buttons.icon');
                    unset($iconsList['non']);
                    ?>
                    <select name="setting_data[{{ $key }}][{{ $tab }}_subscribe_btn_icon]"
                        class="form-control btn-preview-icon">
                        @foreach ($iconsList as $key4 => $item)
                            <option value="{{ $key4 }}" data-icon="{{ $key4 }}"
                                @if ($subscribeBtnIconVal == $key4) selected @endif>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>
            <h4>Блок "Читати також"</h4>

            <div class="form-group row">
                <label class="col-md-3 text-right">{{ __('Title') }}</label>
                <div class="col-md-9">
                    <input type="text"
                        name="setting_data[{{ $key }}][{{ $tab }}_see_also_title]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_see_also_title', $data[$key][$tab . '_see_also_title'][0]['value'] ?? '') }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_see_also_title') ? ' is-invalid' : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 text-right">Текст кнопки</label>
                <div class="col-md-9">
                    <input type="text"
                        name="setting_data[{{ $key }}][{{ $tab }}_see_also_btn_name]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_see_also_btn_name', $data[$key][$tab . '_see_also_btn_name'][0]['value'] ?? '') }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_see_also_btn_name') ? ' is-invalid' : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 text-right">{{ __('Link') }}</label>
                <div class="col-md-9">
                    <input type="text"
                        name="setting_data[{{ $key }}][{{ $tab }}_see_also_btn_link]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_see_also_btn_link', $data[$key][$tab . '_see_also_btn_link'][0]['value'] ?? '') }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_see_also_btn_link') ? ' is-invalid' : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 text-right">Стиль кнопки</label>
                <div class="col-md-9">
                    <?php $btnStyleVal = old('setting_data.' . $key . '.' . $tab . '_see_also_btn_style', $data[$key][$tab . '_see_also_btn_style'][0]['value'] ?? ''); ?>
                    <select name="setting_data[{{ $key }}][{{ $tab }}_see_also_btn_style]"
                        class="form-control">
                        @foreach (config('buttons.type') as $key2 => $item)
                            <option value="{{ $key2 }}" @if ($btnStyleVal == $key2) selected @endif>
                                {{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 text-right">Іконка кнопки</label>
                <div class="col-md-9">
                    <?php $btnIconVal = old('setting_data.' . $key . '.' . $tab . '_see_also_btn_icon', $data[$key][$tab . '_see_also_btn_icon'][0]['value'] ?? ''); ?>
                    <select name="setting_data[{{ $key }}][{{ $tab }}_see_also_btn_icon]"
                        class="form-control btn-preview-icon">
                        @foreach (config('buttons.icon') as $key3 => $item)
                            <option value="{{ $key3 }}" data-icon="{{ $key3 }}"
                                @if ($btnIconVal == $key3) selected @endif>{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>
            @include('admin.settings.tabs._generate', [
                'generate_route' => route('articles.meta-generate'),
            ])
        </div>
    @endforeach
</div>

<?php $defaultLang = config('translatable.locale'); ?>

<hr>

<div class="form-group row">
    <label class="col-md-3 text-right"
        for="setting_{{ $tab }}_per_page_{{ $defaultLang }}">{{ __('Quantity entries per page') }}</label>
    <div class="col-md-9">
        <input type="text" name="setting_data[{{ $defaultLang }}][{{ $tab }}_per_page]"
            value="{{ old('setting_data.' . $defaultLang . '.' . $tab . '_per_page', $data[$defaultLang][$tab . '_per_page'][0]['value'] ?? '') }}"
            id="setting_{{ $tab }}_per_page_{{ $defaultLang }}"
            class="form-control{{ $errors->has('setting_data.' . $defaultLang . '.' . $tab . '_per_page') ? ' is-invalid' : '' }}">
    </div>
</div>
