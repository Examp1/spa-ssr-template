<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                <div class="col-lg-2 mb-3">
                    <label>{{ __('Icon 1') }}</label>
                    {{ media_preview_box($field['name'] . '[#dynamicListPlaceholder][icon_1]') }}
                </div>

                <div class="col-lg-2 mb-3">
                    <label>{{ __('Icon 2') }}</label>
                    {{ media_preview_box($field['name'] . '[#dynamicListPlaceholder][icon_2]') }}
                </div>

                <div class="col-lg-7 input-group-sm">
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][title]"
                        placeholder="{{ __('Title') }}" class="form-control mb-1" disabled>
                    <textarea name="{{ $field['name'] }}[#dynamicListPlaceholder][text]" placeholder="{{ __('Text') }}"
                        class="summernote form-control" disabled></textarea>
                </div>

                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger remove-item float-right">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="items-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                <div data-item-id="{{ $key }}"
                    class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                    <div class="col-lg-2 mb-3">
                        <label>{{ __('Icon 1') }}</label>
                        {{ media_preview_box($field['name'] . '[' . $key . '][icon_1]', $value['icon_1'] ?? null, $errors) }}
                    </div>

                    <div class="col-lg-2 mb-3">
                        <label>{{ __('Icon 2') }}</label>
                        {{ media_preview_box($field['name'] . '[' . $key . '][icon_2]', $value['icon_2'] ?? null, $errors) }}
                    </div>

                    <div class="col-lg-7 input-group-sm">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][title]"
                            placeholder="{{ __('Title') }}"
                            value="{{ old($field['name'] . '.' . $key . '.title', $value['title'] ?? '') }}"
                            class="form-control mb-1">
                        <textarea name="{{ $field['name'] }}[{{ $key }}][text]" placeholder="{{ __('Text') }}"
                            class="summernote form-control">{{ old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') }}</textarea>
                    </div>

                    <div class="col-lg-1">
                        <button type="button"
                            class="btn btn-danger remove-item float-right">{{ __('Delete') }}</button>
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
        class="btn btn-success text-white btn-sm add-item-{{ studly_case($field['name']) }}">{{ __('Add') }}</button>
</div>

@push('scripts')
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
