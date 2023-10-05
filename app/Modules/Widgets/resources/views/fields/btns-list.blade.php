<?php
$types = [
    'fill' => 'Із заливкою',
    'fill_stretch' => 'Із заливкою (на всю ширину)',
    'stroke' => 'З бордером',
    'stroke_stretch' => 'З бордером (на всю ширину)',
    'simple' => 'Посилання',
    'text' => 'Текст',
];
$icons = [
    'non'            => 'Без іконки',
    'icon-owl'       => 'icon-owl',
    'icon-cart'      => 'icon-cart',
    'icon-search'    => 'icon-search',
    'icon-delete'    => 'icon-delete',
    'icon-check'     => 'icon-check',
    'icon-close'     => 'icon-close',
    'icon-loader'    => 'icon-loader',
    'icon-star-b'    => 'icon-star-b',
    'icon-star-o'    => 'icon-star-o',
    'icon-arrow'     => 'icon-arrow',
    'icon-plus'      => 'icon-plus',
    'icon-facebook'  => 'icon-facebook',
    'icon-favorit'   => 'icon-favorit',
    'icon-instagram' => 'icon-instagram',
    'icon-tiktok'    => 'icon-tiktok',
    'icon-youtube'   => 'icon-youtube',
];

$formsList = \App\Modules\Forms\Models\Form::query()
    ->pluck('name', 'id')
    ->toArray();
?>

<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2 qwert">

                <div class="col-5 input-group-sm">
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][text]" placeholder="Текст"
                        class="form-control mb-1 btn-preview-text" disabled>

                    <select name="{{ $field['name'] }}[#dynamicListPlaceholder][type_link]"
                        class="form-control type_link">
                        <option value="link">Довільне посилання</option>
                        <option value="form">Форма</option>
                    </select>

                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][link]"
                        placeholder="Посилання" class="form-control type_link_link mb-1 btn-preview-link" disabled>

                    <select name="{{ $field['name'] }}[#dynamicListPlaceholder][form_id]"
                        class="form-control type_link_form" style="display: none">
                        @foreach ($formsList as $formListKey => $formList)
                            <option value="{{ $formListKey }}">{{ $formList }}</option>
                        @endforeach
                    </select>

                    <select name="{{ $field['name'] }}[#dynamicListPlaceholder][type]"
                        class="form-control w-btn-icon-select2 btn-preview-type">
                        @foreach ($types as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    <select name="{{ $field['name'] }}[#dynamicListPlaceholder][icon]"
                        class="form-control w-row-icon-select2 btn-preview-icon" style="margin-top: 5px; width: 150px">
                        @foreach ($icons as $key => $item)
                            <option value="{{ $key }}" data-icon="{{ $key }}">{{ $item }}
                            </option>
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

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="items-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                <div data-item-id="{{ $key }}"
                    class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2 qwert">

                    <div class="col-5 input-group-sm">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][text]"
                            placeholder="Текст"
                            value="{{ old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') }}"
                            class="form-control mb-1 btn-preview-text">

                        <?php $type_link = $value['type_link'] ?? ''; ?>
                        <select name="{{ $field['name'] }}[{{ $key }}][type_link]"
                            class="form-control type_link">
                            <option value="link" @if ($type_link == 'link') selected @endif>Довільне посилання
                            </option>
                            <option value="form" @if ($type_link == 'form') selected @endif>Форма</option>
                        </select>

                        <input type="text" style="@if ($type_link != 'link') display: none @endif"
                            name="{{ $field['name'] }}[{{ $key }}][link]" placeholder="Посилання"
                            value="{{ old($field['name'] . '.' . $key . '.link', $value['link'] ?? '') }}"
                            class="form-control type_link_link mb-1 btn-preview-link">

                        <?php $form_id = $value['form_id'] ?? ''; ?>
                        <select name="{{ $field['name'] }}[{{ $key }}][form_id]"
                            class="form-control type_link_form"
                            style="@if ($type_link !== 'form') display: none @endif">
                            @foreach ($formsList as $formListKey => $formList)
                                <option value="{{ $formListKey }}" @if ($form_id == $formListKey) selected @endif>
                                    {{ $formList }}</option>
                            @endforeach
                        </select>

                        <?php
                        $typeVal = old($field['name'] . '.' . $key . '.type', $value['type'] ?? '');
                        $iconVal = old($field['name'] . '.' . $key . '.icon', $value['icon'] ?? '');
                        ?>
                        <select name="{{ $field['name'] }}[{{ $key }}][type]"
                            class="form-control btn-preview-type" style="margin-bottom: 15px">
                            @foreach ($types as $key1 => $item)
                                <option value="{{ $key1 }}" @if ($typeVal == $key1) selected @endif>
                                    {{ $item }}</option>
                            @endforeach
                        </select>
                        <select name="{{ $field['name'] }}[{{ $key }}][icon]"
                            class="form-control w-btn-icon-select2-ready btn-preview-icon"
                            style="margin-top: 5px; width: 150px;">
                            @foreach ($icons as $key2 => $item)
                                <option value="{{ $key2 }}" @if ($iconVal == $key2) selected @endif
                                    data-icon="{{ $key2 }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-5">
                        <div style="width: 100%" class="btn-preview-block">
                            <a href="{{ $value['link'] ?? 'javascript:void(0)' }}" target="_blank"
                                class="style-btn {{ $typeVal }} @if ($iconVal !== 'non') hasIcon @endif">
                                <span>{{ $value['text'] ?? '' }}</span>
                                <i class="@if ($iconVal !== 'non') linkIcon {{ $iconVal }} @endif"
                                    @if ($typeVal === 'text') style="display: none" @endif></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-2">
                        <button type="button"
                            class="btn btn-danger remove-item float-right text-white">Видалити</button>
                    </div>

                    @error($field['name'] . '.' . $key)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>

    <button type="button"
        class="btn btn-success text-white btn-sm add-item-{{ studly_case($field['name']) }}">Додати</button>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        function formatStateIcon(state) {
            if (!state.id) {
                return state.text;
            }
            if (state.element.dataset.icon && state.element.dataset.icon !== "non") {
                var $state = $(
                    '<span class="' + state.element.dataset.icon + '"></span>'
                );
            } else {
                var $state = $(
                    '<span> ' + state.text + '</span>'
                );
            }
            return $state;
        };
        $(document).on('click', '.add-item-{{ studly_case($field['name']) }}', function() {
            const parent = $(this).parent();
            const template = parent.find('.item-template');
            const container = parent.find('.items-container');

            create_item(template, container, '#dynamicListPlaceholder');

            container.find('input, textarea').prop('disabled', false);

            container.find('textarea').each(function() {
                if ($(this).hasClass('summernote')) {
                    $(this).summernote(summernote_options);
                }
            });

            container.find('.select2-internal').each(function() {
                $(this).select2({});
            });

            container.find('.w-row-icon-select2').each(function() {
                $(this).select2({
                    minimumResultsForSearch: -1,
                    templateResult: formatStateIcon,
                    templateSelection: formatStateIcon,
                });
            });

            $(".btn-preview-text").trigger("input");
            $(".btn-preview-link").trigger("input");
            $(".btn-preview-type").trigger("change");
            $(".btn-preview-icon").trigger("change");
        });

        $('.items-container').find('textarea').each(function() {
            if ($(this).hasClass('summernote')) {
                $(this).summernote(summernote_options);
            }
        });

        $(document).on('click', '.remove-item', function() {
            $(this).parents('.item-group').remove();
        });

        setTimeout(function() {
            $(".w-btn-icon-select2-ready").each(function() {
                $(this).select2({
                    templateResult: formatStateIcon,
                    templateSelection: formatStateIcon,
                });
            });
        }, 500);
    </script>
@endpush
