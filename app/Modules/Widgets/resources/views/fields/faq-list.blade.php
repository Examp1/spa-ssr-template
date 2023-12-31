<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                <?php
                $list = \App\Models\Faq::query()
                    ->leftJoin('faq_translations', 'faq_translations.faq_id', '=', 'faqs.id')
                    ->where('faq_translations.lang', config('translatable.locale'))
                    ->select(['faqs.id', 'faq_translations.question'])
                    ->pluck('faq_translations.question', 'id')
                    ->toArray();
                ?>
                <div class="col-10">
                    <select class="select2-field" style="width: 100%" name="{{ $field['name'] }}[#dynamicListPlaceholder][faq_id]">
                        @if (count($list))
                            @foreach ($list as $key => $text)
                                <option value="{{ $key }}">{{ $text }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-2">
                    <button type="button"
                            class="btn btn-danger remove-item float-right text-white">{{ __('Remove') }}</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="items-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                <div data-item-id="{{ $key }}"
                    class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                    <div class="col-10">
                    <select class="select2-field-shown" style="width: 100%" name="{{ $field['name'] }}[{{ $key }}][faq_id]">
                        @if (count($list))
                            @foreach ($list as $id => $text)
                                <option value="{{ $id }}" @if ($value['faq_id'] == $id) selected @endif>
                                    {{ $text }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    </div>

                    <div class="col-2">
                        <button type="button"
                            class="btn btn-danger remove-item float-right text-white">{{ __('Remove') }}</button>
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

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2-field-shown').each(function() {
                $(this).select2({});
            });
        });

        $(document).on('click', '.add-item-{{ studly_case($field['name']) }}', function() {
            const parent = $(this).parent();
            const template = parent.find('.item-template');
            const container = parent.find('.items-container');

            create_item(template, container, '#dynamicListPlaceholder');

            container.find('input, textarea').prop('disabled', false);

            container.find('.select2-field').each(function() {
                $(this).select2({
                    placeholder: 'Виберіть елемент'
                });
            });
        });

        $(document).on('click', '.remove-item', function() {
            $(this).parents('.item-group').remove();
        });
    </script>
@endpush
