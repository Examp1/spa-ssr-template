<?php
$list = \Owlwebdev\Ecom\Models\Category::query()
    ->where('parent_id', 0)
    ->active()
    ->get();
?>

<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                <div class="col-lg-9 mb-3">
                    <label>Категорія</label>
                    <select class="select2-field" name="{{ $field['name'] }}[#dynamicListPlaceholder][item_id]"
                        style="width: 100%">
                        <option value="" selected>---</option>
                        @foreach ($list as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
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

                    <div class="col-lg-5 mb-3">
                        <label>Категорія</label>
                        <select class="select2-field-shown category-select"
                            name="{{ $field['name'] }}[{{ $key }}][item_id]" style="width: 100%">
                            @foreach ($list as $item)
                                @php($sel = old($field['name'] . '.' . $key . '.item_id', $value['item_id'] ?? 0))
                                <option value="{{ $item->id }}" @if ($sel == $item->id) selected @endif>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2">
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
        class="btn btn-success text-white btn-sm add-item-{{ studly_case($field['name']) }}">{{ __('Create') }}</button>
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
