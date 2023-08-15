<script type="text/javascript">
    (function() {
        $(document).on('click', '.add-table-row_{{ $lang }}', function() {
            const count = $(this).parent().find('.item-row-template-count>option:selected').val();
            const template = $(this).parent().find('.item-row-template' + count);
            const container = $(this).parent().find('.items-row-container');

            create_item(template, container, '#tableRowPlaceholder' + count);

            container.find('input').prop('disabled', false);
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

        $(document).on('click', '.text-n-columns-add-btn-list-item_{{ $lang }}', function() {
            const template = $(this).parent().find('.text-n-columns-btn-list-template');
            const container = $(this).parent().find('.text-n-columns-btn-list-container');

            create_item(template, container, '#btnInputPlaceholder');

            container.find('input, textarea').prop('disabled', false);

            container.find('.btn-icon-select2').each(function() {
                $(this).select2({
                    templateResult: formatStateIcon,
                    templateSelection: formatStateIcon,
                });
            });

            $(".btn-preview-text").trigger("input");
            $(".btn-preview-link").trigger("input");
            $(".btn-preview-type").trigger("change");
            $(".btn-preview-icon").trigger("change");
        });
    })();
</script>
