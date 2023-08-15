<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                <div class="col-lg-10 mb-3">
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][number]" placeholder="Цифра"
                        class="form-control mb-1" disabled>
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][title]"
                        placeholder="Заголовок" class="form-control mb-1" disabled>
                    <select name="{{ $field['name'] }}[#dynamicListPlaceholder][title_font_size]" class="form-control">
                        <option value="normal">Звичайний</option>
                        <option value="small">Маленький</option>
                    </select>
                    <textarea name="{{ $field['name'] }}[#dynamicListPlaceholder][text]" placeholder="Текст" class="summernote"></textarea>
                </div>

                <div class="col-lg-2">
                    <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="items-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                <div data-item-id="{{ $key }}"
                    class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                    <div class="col-lg-10 mb-3">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][number]"
                            value="{{ old($field['name'] . '.' . $key . '.number', $value['number'] ?? '') }}"
                            placeholder="Цифра" class="form-control mb-1">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][title]"
                            value="{{ old($field['name'] . '.' . $key . '.title', $value['title'] ?? '') }}"
                            placeholder="Заголовок" class="form-control mb-1">
                        <?php $title_font_size_val = old($field['name'] . '.' . $key . '.title_font_size', $value['title_font_size'] ?? ''); ?>
                        <select name="{{ $field['name'] }}[{{ $key }}][title_font_size]"
                            class="form-control">
                            <option value="normal" @if ($title_font_size_val == 'normal') selected @endif>Звичайний</option>
                            <option value="small" @if ($title_font_size_val == 'small') selected @endif>Маленький</option>
                        </select>
                        <textarea name="{{ $field['name'] }}[{{ $key }}][text]" placeholder="Текст" class="summernote">{{ old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') }}</textarea>
                    </div>

                    <div class="col-lg-2">
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
    <style>
        input.disabled {
            pointer-events: none;
            background-color: #e9ecef;
        }
    </style>
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