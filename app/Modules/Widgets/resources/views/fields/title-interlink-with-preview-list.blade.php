<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">

                <div class="col-lg-11 input-group-sm">
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][title]"
                        placeholder="Заголовок" class="form-control mb-1" disabled>
                    <textarea name="{{ $field['name'] }}[#dynamicListPlaceholder][text]" placeholder="Текст" class="summernote"></textarea>
                    {{ media_preview_box($field['name'] . '[#dynamicListPlaceholder][image]') }}
                    @include('admin.pieces.fields.interlink', [
                        'lang' => studly_case($field['name']),
                        'name_type' => $field['name'] . '[#dynamicListPlaceholder][interlink_type]',
                        'name_val' => $field['name'] . '[#dynamicListPlaceholder][interlink_val]',
                    ])
                </div>

                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="items-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                <div data-item-id="{{ $key }}"
                    class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">

                    <div class="col-lg-11 input-group-sm">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][title]"
                            placeholder="Заголовок"
                            value="{{ old($field['name'] . '.' . $key . '.title', $value['title'] ?? '') }}"
                            class="form-control mb-1">
                        <textarea name="{{ $field['name'] }}[{{ $key }}][text]" placeholder="Текст" class="summernote">{{ old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') }}</textarea>
                        {{ media_preview_box($field['name'] . '[' . $key . '][image]', $value['image'] ?? null, $errors) }}
                        <?php
                        $interlinkSelType = old($field['name'] . '.' . $key . '.interlink_type', $value['interlink_type'] ?? '');
                        $interlinkSelVal = old($field['name'] . '.' . $key . '.interlink_val', $value['interlink_val'] ?? '');
                        ?>
                        @include('admin.pieces.fields.interlink', [
                            'lang' => studly_case($field['name']),
                            'name_type' => $field['name'] . '[' . $key . '][interlink_type]',
                            'name_val' => $field['name'] . '[' . $key . '][interlink_val]',
                            'sel_type' => $interlinkSelType,
                            'sel_val' => $interlinkSelVal,
                        ])
                    </div>

                    <div class="col-lg-1">
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
        });

        $('.items-container').find('textarea').each(function() {
            if ($(this).hasClass('summernote')) {
                $(this).summernote(summernote_options);
            }
        });

        $(document).on('click', '.remove-item', function() {
            $(this).parents('.item-group').remove();
        });

        /* INTERLINK component */
        $(document).on('change', '.interlink-select-type-{{ studly_case($field['name']) }}', function() {
            let type = $(this).val();
            console.log(type)
            $(this).siblings('.select-type').hide();
            $(this).siblings('.select-type-' + type).show();
        });

        $('.select2-internal-init').each(function() {
            $(this).select2({});
        });
    </script>
@endpush
