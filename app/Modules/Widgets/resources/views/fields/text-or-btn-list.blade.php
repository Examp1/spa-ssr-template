<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                <div class="col-lg-7 input-group-sm">
                    <div class="card card-t-text">
                        <div class="card-body">
                            <label>
                                <input type="radio" class="radio_input radio_text" checked
                                    name="text_or_btn_#dynamicListPlaceholder">
                                Текст
                            </label>
                            <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][text]"
                                placeholder="Текст" class="form-control mb-1">
                        </div>
                    </div>

                    <div class="card card-t-btn">
                        <div class="card-body">
                            <label>
                                <input type="radio" class="radio_input radio_btn"
                                    name="text_or_btn_#dynamicListPlaceholder">
                                Кнопка
                            </label>
                            <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][btn_text]"
                                placeholder="Текст на кнопці" class="form-control mb-1" disabled>
                            <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][btn_link]"
                                placeholder="Посилання" class="form-control mb-1" disabled>
                        </div>
                    </div>
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
                    <div class="col-lg-7 input-group-sm">
                        <div class="card card-t-text">
                            <div class="card-body">
                                <label>
                                    <input type="radio" class="radio_input radio_text"
                                        @if (old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') !== '') checked @endif
                                        name="text_or_btn_{{ $key }}">
                                    Текст
                                </label>
                                <input type="text" name="{{ $field['name'] }}[{{ $key }}][text]"
                                    value="{{ old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') }}"
                                    placeholder="Текст" @if (old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') == '') disabled @endif
                                    class="form-control mb-1">
                            </div>
                        </div>

                        <div class="card card-t-btn">
                            <div class="card-body">
                                <label>
                                    <input type="radio" class="radio_input radio_btn"
                                        @if (old($field['name'] . '.' . $key . '.btn_text', $value['btn_text'] ?? '') !== '') checked @endif
                                        name="text_or_btn_{{ $key }}">
                                    Кнопка
                                </label>
                                <input type="text" name="{{ $field['name'] }}[{{ $key }}][btn_text]"
                                    value="{{ old($field['name'] . '.' . $key . '.btn_text', $value['btn_text'] ?? '') }}"
                                    placeholder="Текст на кнопці" class="form-control mb-1"
                                    @if (old($field['name'] . '.' . $key . '.btn_text', $value['btn_text'] ?? '') == '') disabled @endif>
                                <input type="text" name="{{ $field['name'] }}[{{ $key }}][btn_link]"
                                    value="{{ old($field['name'] . '.' . $key . '.btn_link', $value['btn_link'] ?? '') }}"
                                    placeholder="Посилання" class="form-control mb-1"
                                    @if (old($field['name'] . '.' . $key . '.btn_text', $value['btn_text'] ?? '') == '') disabled @endif>
                            </div>
                        </div>
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

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', ".radio_input.radio_text", function() {
                $(this).closest('.card-body').find("input[type='text']").prop('disabled', false);
                $(this).closest('.item-template').find(".card-t-btn input[type='text']").prop('disabled',
                    true);
                $(this).closest('.item-template').find(".card-t-btn input[type='text']").val('');
            });

            $(document).on('change', ".radio_input.radio_btn", function() {
                $(this).closest('.card-body').find("input[type='text']").prop('disabled', false);
                $(this).closest('.item-template').find(".card-t-text input[type='text']").prop('disabled',
                    true);
                $(this).closest('.item-template').find(".card-t-text input[type='text']").val('');
            });
        });

        $(document).on('click', '.add-item-{{ studly_case($field['name']) }}', function() {
            const parent = $(this).parent();
            const template = parent.find('.item-template');
            const container = parent.find('.items-container');

            create_item(template, container, '#dynamicListPlaceholder');

            // container.find('input, textarea').prop('disabled', false);

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
