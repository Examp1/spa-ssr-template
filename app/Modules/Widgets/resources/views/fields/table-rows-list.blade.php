<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}"></label>
    <div class="input-group mb-3">
        <div style="display: none;">
            @foreach ($field['columns'] as $col_count => $width)
                <div data-item-id="#dynamicListPlaceholder{{ $col_count }}"
                    class="table-row-template{{ $col_count }} item-group mb-1 bg-light border border-dark p-1 col-12">
                    <table class="table table-hover text-nowrap">
                        <tbody>
                            <tr>
                                @for ($i = 0; $i < $col_count; $i++)
                                    <td>
                                        <div class="form-group mb-1">
                                            <label>Ширина</label>
                                            <select class="form-control item-row-template-count"
                                                name="{{ $field['name'] }}[#dynamicListPlaceholder{{ $col_count }}][{{ $i }}][column_width]">
                                                @foreach ($field['cols_width'] as $w_value => $w_text)
                                                    <option value="{{ $w_value }}">{{ $w_text }}</option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="form-group mb-1">
                                            <label>Текст<button type="button"
                                                    class="btn btn-success btn-sm edit-item ml-1"><i
                                                        class="fas fa-edit text-green"></i></button><button
                                                    type="button" class="btn btn-succes btn-sm edit-item-del"><i
                                                        class="fas fa-window-close text-red"></i></button></label>
                                            <textarea class="form-control small_summernote"
                                                name="{{ $field['name'] }}[#dynamicListPlaceholder{{ $col_count }}][{{ $i }}][column_text]"></textarea>
                                        </div>
                                    </td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-1 text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                    </div>
                </div>
            @endforeach
        </div>

        <input type="hidden" name="{{ $field['name'] }}" value="">

        <div class="tables-container w-100">
            @foreach ((array) old($field['name'], $value) as $key => $value)
                @if ($value)
                    <div data-item-id="{{ $key }}"
                        class="item-template item-group mb-1 bg-light border border-dark p-1 col-12">
                        <table class="table table-hover text-nowrap">
                            <tbody>
                                <tr>
                                    @foreach ((array) $value as $col => $text)
                                        <td>
                                            <select class="form-control item-row-template-count"
                                                name="{{ $field['name'] }}[{{ $key }}][{{ $col }}][column_width]">
                                                @foreach ($field['cols_width'] as $w_value => $w_text)
                                                    <option value="{{ $w_value }}"
                                                        @if ($text['column_width'] == $w_value) selected @endif>
                                                        {{ $w_text }}</option>
                                                @endforeach
                                            </select>
                                            <div class="form-group mb-1">
                                                <label>Колонка<button type="button"
                                                        class="btn btn-success btn-sm edit-item ml-1"><i
                                                            class="fas fa-edit text-green"></i></button><button
                                                        type="button" class="btn btn-succes btn-sm edit-item-del"><i
                                                            class="fas fa-window-close text-red"></i></button></label>
                                                <textarea class="form-control small_summernote"
                                                    name="{{ $field['name'] }}[{{ $key }}][{{ $col }}][column_text]">{{ $text['column_text'] ?? '' }}</textarea>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-1 text-center">
                            <button type="button"
                                class="btn btn-outline-danger btn-sm remove-row-item">Видалити</button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <button type="button"
        class="btn btn-success text-white btn-sm add-tables-{{ studly_case($field['name']) }}">Додати
        {{ $field['label'] }}</button>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).on('click', '.add-tables-{{ studly_case($field['name']) }}', function() {
            const count = $(this).closest('.card-body').find(
                '.item-row-template-count{{ $field['template'] }}>option:selected').val();
            const parent = $(this).parent();
            const template = parent.find('.table-row-template' + count);
            const container = parent.find('.tables-container');

            create_item(template, container, '#dynamicListPlaceholder' + count);

            container.find('input, textarea').prop('disabled', false);
        });

        function init_small_summernote(component_container) {
            component_container.find('textarea').each(function() {
                if ($(this).hasClass('small_summernote') && $(this).is(':visible')) {
                    $(this).summernote({
                        height: 250,
                        minHeight: null,
                        maxHeight: null,
                        maxWidth: '100%',
                        lang: 'uk-UA',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'italic', 'clear']],
                            // ['fontname', ['fontname']],
                            // ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            // ['table', ['table']],
                            ['insert', ['link', /*'picture', 'video'*/ ]],
                            ['view', ['fullscreen', 'codeview']],
                        ],
                    });
                }
            });
        }

        function del_small_summernote(component_container) {
            component_container.find('textarea').each(function() {
                if ($(this).hasClass('small_summernote')) {
                    $(this).summernote('destroy');
                }
            });
        }

        $(document).on('click', '.edit-item', function() {
            console.log($(this).parent().parent());
            init_small_summernote($(this).parent().parent());
        });

        $(document).on('click', '.edit-item-del', function() {
            console.log($(this).parent().parent());
            del_small_summernote($(this).parent().parent());
        });

        $(document).on('click', '.remove-row-item', function() {
            $(this).parents('.item-group').remove();
        });
    </script>
@endpush
