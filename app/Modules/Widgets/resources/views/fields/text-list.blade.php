<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ $field['label'] }}</label>

    <div class="input-group mb-3">
        <div style="display: none;">
            <div data-item-id="#dynamicListPlaceholder"
                class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                <div class="col-lg-9 input-group-sm">
                    <input type="text" name="{{ $field['name'] }}[#dynamicListPlaceholder][item]" placeholder="Елемент"
                        class="form-control mb-1" disabled>
                </div>

                <div class="col-lg-2">
                    <button type="button" class="btn btn-danger remove-item float-right">Видалити</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="items-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                <div data-item-id="{{ $key }}"
                    class="item-template item-group input-group mb-3 align-items-center border border-grey-light pt-2 pb-2">
                    <div class="col-lg-9 input-group-sm">
                        <input type="text" name="{{ $field['name'] }}[{{ $key }}][item]"
                            placeholder="Елемент"
                            value="{{ old($field['name'] . '.' . $key . '.item', $value['item'] ?? '') }}"
                            class="form-control mb-1">
                    </div>

                    <div class="col-lg-2">
                        <button type="button" class="btn btn-danger remove-item float-right">Видалити</button>
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
        $(document).on('click', '.add-item-{{ studly_case($field['name']) }}', function() {
            const parent = $(this).parent();
            const template = parent.find('.item-template');
            const container = parent.find('.items-container');

            create_item(template, container, '#dynamicListPlaceholder');

            container.find('input, textarea').prop('disabled', false);
        });

        $(document).on('click', '.remove-item', function() {
            $(this).parents('.item-group').remove();
        });
    </script>
@endpush
