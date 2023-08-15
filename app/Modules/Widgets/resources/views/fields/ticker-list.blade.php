<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholderText"
                class="item-template-text item-group border border-grey-light">
                <div class="row align-items-center" style="padding: 10px">
                    <div class="col-md-9">
                        <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholderText][text]"
                            placeholder="Текст" class="form-control mb-1" disabled>
                    </div>
                    <div class="col-md-3">
                        <button type="button"
                            class="btn btn-danger remove-item float-right text-white">Видалити</button>
                    </div>
                </div>
            </div>
            <div data-item-id="#dynamicListPlaceholderImage"
                class="item-template-image item-group border border-grey-light">
                <div class="row align-items-center" style="padding: 10px">
                    <div class="col-md-9">
                        {{ media_preview_box($field['name'] . '[#dynamicListPlaceholderImage][image]') }}
                    </div>
                    <div class="col-md-3">
                        <button type="button"
                            class="btn btn-danger remove-item float-right text-white">Видалити</button>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="items-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                <div data-item-id="{{ $key }}" class="item-template item-group border border-grey-light">
                    <div class="row align-items-center" style="padding: 10px">
                        <div class="col-md-9">
                            @if (array_keys($value)[0] === 'text')
                                <input type="text" name="{{ $field['name'] }}[{{ $key }}][text]"
                                    placeholder="Текст"
                                    value="{{ old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') }}"
                                    class="form-control mb-1">
                            @else
                                {{ media_preview_box($field['name'] . '[' . $key . '][image]', $value['image'] ?? null, $errors) }}
                            @endif
                        </div>
                        <div class="col-md-3">
                            <button type="button"
                                class="btn btn-danger remove-item float-right text-white">Видалити</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button type="button"
        class="btn btn-success text-white btn-sm add-item-text-{{ studly_case($field['name']) }}">Додати
        текст</button>
    <button type="button"
        class="btn btn-success text-white btn-sm add-item-image-{{ studly_case($field['name']) }}">Додати
        зображення</button>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '.add-item-text-{{ studly_case($field['name']) }}', function() {
            const parent = $(this).parent();
            const template = parent.find('.item-template-text');
            const container = parent.find('.items-container');

            create_item(template, container, '#dynamicListPlaceholderText');

            container.find('input, textarea').prop('disabled', false);

            container.find('textarea').each(function() {
                if ($(this).hasClass('summernote')) {
                    $(this).summernote(summernote_options);
                }
            });

            container.find('.select2-field').each(function() {
                $(this).select2({});
            });
        });

        $(document).on('click', '.add-item-image-{{ studly_case($field['name']) }}', function() {
            const parent = $(this).parent();
            const template = parent.find('.item-template-image');
            const container = parent.find('.items-container');

            create_item(template, container, '#dynamicListPlaceholderImage');

            container.find('input, textarea').prop('disabled', false);

            container.find('textarea').each(function() {
                if ($(this).hasClass('summernote')) {
                    $(this).summernote(summernote_options);
                }
            });

            container.find('.select2-field').each(function() {
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
    </script>
@endpush
