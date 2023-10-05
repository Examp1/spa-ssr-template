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
                    for="setting_{{ $tab }}_title_{{ $key }}">Заголовок</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][{{ $tab }}_title]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_title', $data[$key][$tab . '_title'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_title_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_title') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_description_{{ $key }}">Опис</label>
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
                    for="setting_{{ $tab }}_meta_title_{{ $key }}">Title</label>
                <div class="col-md-9">
                    <input type="text" name="setting_data[{{ $key }}][{{ $tab }}_meta_title]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_meta_title', $data[$key][$tab . '_meta_title'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_meta_title_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_meta_title') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 text-right"
                    for="setting_{{ $tab }}_meta_description_{{ $key }}">Description</label>
                <div class="col-md-9">
                    <input type="text"
                        name="setting_data[{{ $key }}][{{ $tab }}_meta_description]"
                        value="{{ old('setting_data.' . $key . '.' . $tab . '_meta_description', $data[$key][$tab . '_meta_description'][0]['value'] ?? '') }}"
                        id="setting_{{ $tab }}_meta_description_{{ $key }}"
                        class="form-control{{ $errors->has('setting_data.' . $key . '.' . $tab . '_meta_description') ? ' is-invalid' : '' }}">
                </div>
            </div>

            <hr>
            @include('admin.settings.tabs._generate', ['generate_route' => route('categories.meta-generate')])
        </div>
    @endforeach
</div>

<?php $defaultLang = config('translatable.locale'); ?>

<hr>

<div class="form-group row">
    <label class="col-md-3 text-right" for="setting_{{ $tab }}_per_page_{{ $defaultLang }}">{{ __('Quantity entries per page') }}</label>
    <div class="col-md-9">
        <input type="text" name="setting_data[{{ $defaultLang }}][{{ $tab }}_per_page]"
            value="{{ old('setting_data.' . $defaultLang . '.' . $tab . '_per_page', $data[$defaultLang][$tab . '_per_page'][0]['value'] ?? '') }}"
            id="setting_{{ $tab }}_per_page_{{ $defaultLang }}"
            class="form-control{{ $errors->has('setting_data.' . $defaultLang . '.' . $tab . '_per_page') ? ' is-invalid' : '' }}">
    </div>
</div>
