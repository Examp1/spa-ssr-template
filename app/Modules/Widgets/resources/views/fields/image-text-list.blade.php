<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                <div class="col-lg-3 mb-3 input-group-sm">
                    <label>{{ __('Image position') }}</label>
                    <select name="{{ $field['name'] }}[#dynamicListPlaceholder][image_position]" class="form-control mb-3"
                        disabled>
                        <option value="left">{{ __('Left') }}</option>
                        <option value="right">{{ __('Right') }}</option>
                    </select>

                    <label>{{ __('Image') }}</label>
                    {{ media_preview_box($field['name'] . '[#dynamicListPlaceholder][image]') }}

                    <hr />

                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][label]"
                        placeholder="{{ __('Label') }}" class="form-control mb-1" disabled>
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][class]"
                        placeholder="{{ __('Class') }}" class="form-control mb-1" disabled>
                </div>

                <div class="col-lg-8 input-group-sm">
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][title]"
                        placeholder="{{ __('Title') }}" class="form-control mb-1" disabled>
                    <textarea name="{{ $field['name'] }}[#dynamicListPlaceholder][text]" placeholder="{{ __('Text') }}"
                        class="summernote form-control" disabled></textarea>
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][link_text]"
                        placeholder="{{ __('Link text') }}" class="form-control mb-1" disabled>
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][link_source]"
                        placeholder="{{ __('Link source') }}" class="form-control mb-1" disabled>
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
                    <div class="col-lg-3 mb-3 input-group-sm">
                        <label>{{ __('Image position') }}</label>
                        <select name="{{ $field['name'] }}[{{ $key }}][image_position]"
                            class="form-control mb-3">
                            <option value="left" @if ($value['image_position'] == 'left') selected @endif>
                                {{ __('Left') }}</option>
                            <option value="right" @if ($value['image_position'] == 'right') selected @endif>
                                {{ __('Right') }}</option>
                        </select>

                        <label>{{ __('Icon 1') }}</label>
                        {{ media_preview_box($field['name'] . '[' . $key . '][image]', $value['image'] ?? null, $errors) }}

                        <hr />

                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][label]"
                            placeholder="{{ __('Label') }}"
                            value="{{ old($field['name'] . '.' . $key . '.label', $value['label'] ?? '') }}"
                            class="form-control mb-1">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][class]"
                            placeholder="{{ __('Class') }}"
                            value="{{ old($field['name'] . '.' . $key . '.class', $value['class'] ?? '') }}"
                            class="form-control mb-1">
                    </div>

                    <div class="col-lg-8 input-group-sm">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][title]"
                            placeholder="{{ __('Title') }}"
                            value="{{ old($field['name'] . '.' . $key . '.title', $value['title'] ?? '') }}"
                            class="form-control mb-1">
                        <textarea name="{{ $field['name'] }}[{{ $key }}][text]" placeholder="{{ __('Text') }}"
                            class="summernote form-control">{{ old($field['name'] . '.' . $key . '.text', $value['text'] ?? '') }}</textarea>
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][link_text]"
                            placeholder="{{ __('Link text') }}"
                            value="{{ old($field['name'] . '.' . $key . '.link_text', $value['link_text'] ?? '') }}"
                            class="form-control mb-1">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][link_source]"
                            placeholder="{{ __('Link source') }}"
                            value="{{ old($field['name'] . '.' . $key . '.link_source', $value['link_source'] ?? '') }}"
                            class="form-control mb-1">
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

            container.find('input, textarea, select').prop('disabled', false);

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
