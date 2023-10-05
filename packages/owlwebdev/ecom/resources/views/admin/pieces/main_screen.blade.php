<?php
$formsList = \App\Modules\Forms\Models\Form::query()
    ->where('lang', $lang)
    ->get();
?>
<div class="form-group row">
    <label class="col-md-3 text-right">{{ __('Main screen') }}</label>
    <div class="col-md-9">
        <div id="main_screen_{{ $lang }}" class="card card-default card-component"
            style="position: relative;top:0;left:0;">
            <div class="display-layout"></div>
            <div class="card card-outline card-info pt-1 pr-3 pb-1 pl-3 mb-0">
                <div class="card-title move-label d-inline" style="margin-bottom: 2px!important;">
                    {{ __('Settings') }}
                    <span class="float-right">
                        <div class="d-inline component-visibility-switch custom-switch custom-switch-off-danger custom-switch-on-success"
                            style="top:-2px; right: 15px;">
                            <input type="hidden" name="main_screen[data][{{ $lang }}][visibility]" value="0">
                            <input type="checkbox" name="main_screen[data][{{ $lang }}][visibility]"
                                class="custom-control-input show-hide-checkbox"
                                id="component_visibility_main_screen_{{ $lang }}" value="1"
                                @if (isset($data[$lang]['main_screen']['visibility']) && $data[$lang]['main_screen']['visibility'] == 0)

                                @else
                                    checked
                                @endif
                            >
                            <label class="custom-control-label"
                                for="component_visibility_main_screen_{{ $lang }}"></label>
                        </div>
                        <a href="#collapse_main_screen_{{ $lang }}"
                            @if (isset($data[$lang]['main_screen']['visibility']) && $data[$lang]['main_screen']['visibility'] == 0)
                                class="text-info collapse-button ml-2 collapsed"
                                aria-expanded="false"
                            @else
                                class="text-info collapse-button ml-2"
                            @endif
                            data-toggle="collapse">
                            <i class="far fa-caret-square-up"></i>
                        </a>
                    </span>
                </div>
            </div>

            <div id="collapse_main_screen_{{ $lang }}"
                class="card-body mt-1 collapse {{ (isset($data[$lang]['main_screen']['visibility']) && $data[$lang]['main_screen']['visibility'] == 0) ? '' : 'show' }}">
                <div class="row">
                    <div class="col-md-12">
                        <label for="page_description_{{ $lang }}">{{ __('Content') }}</label>
                        <textarea name="page_data[{{ $lang }}][description]" id="page_description_{{ $lang }}"
                            class="summernote editor" cols="30" rows="10">{{ old('page_data.' . $lang . '.description', $data[$lang]['description'] ?? '') }}</textarea>
                    </div>

                    {{-- <div class="col-md-12">
                        <div class="row">
                            <label class="col-3">{{ __('Image') }}</label>
                            <select class="form-control col-9" style="width: 100px"
                                name="main_screen[data][{{ $lang }}][with_image]">
                                <option value="0" @if (isset($data[$lang]['main_screen']['with_image']) && $data[$lang]['main_screen']['with_image'] == 0) selected @endif>
                                    {{ __('No') }}</option>
                                <option value="1" @if (isset($data[$lang]['main_screen']['with_image']) && $data[$lang]['main_screen']['with_image'] == 1) selected @endif>
                                    {{ __('Yes') }}</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                        <div class="input-group">
                            <div style="display: none;">
                                <div data-item-id="#btnInputPlaceholder"
                                    class="main_screen-btn-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center qwert">
                                    <div class="col-5">
                                        <input type="text"
                                            name="main_screen[data][{{ $lang }}][buttons][#btnInputPlaceholder][text]"
                                            placeholder="Текст" class="form-control mt-3 mb-1 btn-preview-text"
                                            disabled>

                                        <select
                                            name="main_screen[data][{{ $lang }}][buttons][#btnInputPlaceholder][type_link]"
                                            class="form-control type_link">
                                            <option value="link">{{ __('Arbitrary link') }}</option>
                                            <option value="form">{{ __('Form') }}</option>
                                        </select>

                                        <input type="text"
                                            name="main_screen[data][{{ $lang }}][buttons][#btnInputPlaceholder][link]"
                                            placeholder="{{ __('Link') }}"
                                            class="form-control type_link_link mt-3 mb-1 btn-preview-link" disabled>

                                        <select
                                            name="main_screen[data][{{ $lang }}][buttons][#btnInputPlaceholder][form_id]"
                                            class="form-control type_link_form" style="display: none">
                                            @foreach ($formsList as $formList)
                                                <option value="{{ $formList->id }}">{{ $formList->name }}</option>
                                            @endforeach
                                        </select>

                                        <select class="form-control form-control mt-3 mb-1 btn-preview-type"
                                            name="main_screen[data][{{ $lang }}][buttons][#btnInputPlaceholder][type]">
                                            @foreach (config('buttons.type') as $listKey => $listItem)
                                                <option value="{{ $listKey }}">{{ $listItem }}</option>
                                            @endforeach
                                        </select>
                                        {{-- <select class="form-control btn-icon-select2 btn-preview-icon"
                                            style="width: 150px"
                                            name="main_screen[data][{{ $lang }}][buttons][#btnInputPlaceholder][icon]">
                                            @foreach (config('buttons.icon') as $listKey => $listItem)
                                                <option value="{{ $listKey }}" data-icon="{{ $listKey }}">
                                                    {{ $listItem }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>

                                    <div class="col-5">
                                        <div style="width: 100%" class="btn-preview-block">
                                            <a href="javascript:void(0)" target="_blank"><span></span><i></i></a>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <button type="button"
                                            class="btn btn-danger remove-item float-right text-white">{{ __('Remove') }}</button>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="main_screen[data][{{ $lang }}][buttons]"
                                value="">

                            <div class="main_screen-btn-list-container w-100">
                                @isset($data[$lang]['main_screen']['buttons'])
                                    @foreach ($data[$lang]['main_screen']['buttons'] as $k => $value)
                                        <div data-item-id="{{ $k }}"
                                            class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center qwert">
                                            <div class="col-5">
                                                <input type="text"
                                                    name="main_screen[data][{{ $lang }}][buttons][{{ $k }}][text]"
                                                    placeholder="Текст" class="form-control mt-3 mb-1 btn-preview-text"
                                                    value="{{ $value['text'] ?? '' }}">

                                                <?php $type_link = $value['type_link'] ?? ''; ?>
                                                <select
                                                    name="main_screen[data][{{ $lang }}][buttons][{{ $k }}][type_link]"
                                                    class="form-control type_link">
                                                    <option value="link"
                                                        @if ($type_link == 'link') selected @endif>
                                                        {{ __('Arbitrary link') }}</option>
                                                    <option value="form"
                                                        @if ($type_link == 'form') selected @endif>
                                                        {{ __('Form') }}</option>
                                                </select>

                                                <input type="text"
                                                    name="main_screen[data][{{ $lang }}][buttons][{{ $k }}][link]"
                                                    placeholder="{{ __('Link') }}"
                                                    class="form-control type_link_link mt-3 mb-1 btn-preview-link"
                                                    value="{{ $value['link'] ?? '' }}"
                                                    style="@if ($type_link != 'link') display: none @endif">

                                                <?php $form_id = $value['form_id'] ?? ''; ?>
                                                <select
                                                    name="main_screen[data][{{ $lang }}][buttons][{{ $k }}][form_id]"
                                                    class="form-control type_link_form"
                                                    style="@if ($type_link !== 'form') display: none @endif">
                                                    @foreach ($formsList as $formList)
                                                        <option value="{{ $formList->id }}"
                                                            @if ($form_id == $formList->id) selected @endif>
                                                            {{ $formList->name }}</option>
                                                    @endforeach
                                                </select>


                                                <?php $type = $value['type'] ?? ''; ?>
                                                <select class="form-control mt-3 mb-1 btn-preview-type"
                                                    name="main_screen[data][{{ $lang }}][buttons][{{ $k }}][type]">
                                                    @foreach (config('buttons.type') as $listKey => $listItem)
                                                        <option value="{{ $listKey }}"
                                                            @if ($type == $listKey) selected @endif>
                                                            {{ $listItem }}</option>
                                                    @endforeach
                                                </select>
                                                <?php $icon = $value['icon'] ?? ''; ?>
                                                {{-- <select class="form-control btn-icon-select2-ready btn-preview-icon"
                                                    style="width: 150px"
                                                    name="main_screen[data][{{ $lang }}][buttons][{{ $k }}][icon]">
                                                    @foreach (config('buttons.icon') as $listKey => $listItem)
                                                        <option value="{{ $listKey }}"
                                                            @if ($icon == $listKey) selected @endif
                                                            data-icon="{{ $listKey }}">{{ $listItem }}</option>
                                                    @endforeach
                                                </select> --}}
                                            </div>

                                            <div class="col-5">
                                                <div style="width: 100%" class="btn-preview-block">
                                                    <a href="{{ $value['link'] ?? 'javascript:void(0)' }}"
                                                        target="_blank"
                                                        class="style-btn {{ $type }} @if ($icon !== 'non') hasIcon @endif">
                                                        <span>{{ $value['text'] ?? '' }}</span>
                                                        <i class="@if ($icon !== 'non') linkIcon {{ $icon }} @endif"
                                                            @if ($type === 'text') style="display: none" @endif></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-2">
                                                <button type="button"
                                                    class="btn btn-danger remove-item text-white float-right">{{ __('Remove') }}</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endisset
                            </div>
                        </div>

                        <button type="button"
                            class="btn btn-info btn-sm main_screen-add-btn-list-item_{{ $lang }} d-block mt-2">{{ __('Add Button') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).on('click', '.main_screen-add-btn-list-item_{{ $lang }}', function() {
            const template = $(this).parent().find('.main_screen-btn-list-template');
            const container = $(this).parent().find('.main_screen-btn-list-container');

            create_item(template, container, '#btnInputPlaceholder');

            container.find('input, textarea').prop('disabled', false);

            container.find('.btn-icon-select2').each(function() {
                $(this).select2({
                    templateResult: formatStateIcon,
                    templateSelection: formatStateIcon,
                });
            });

            $(".btn-preview-text").trigger("input");
            $(".btn-preview-link").trigger("input");
            $(".btn-preview-type").trigger("change");
            $(".btn-preview-icon").trigger("change");
        });
        $(document).on('click', '.remove-item', function () {
            $(this).parents('.item-group').remove();
        });
    </script>
@endpush
