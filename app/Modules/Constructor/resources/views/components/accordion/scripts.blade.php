<script type="text/javascript">
    (function () {
        $(".summernote_initialized").each(function () {
            $(this).summernote(summernote_options);
        })

        $(document).on('click', '.add-accordion-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.accordion-list-template');
            const container = $(this).parent().find('.accordion-list-container');

            create_item(template, container, '#imageInputPlaceholder1');

            container.find('input, textarea').prop('disabled', false);

            init_small_summernote(container);
        });

        $(document).on('click', '.remove-item', function () {
            $(this).parents('.item-group').remove();
        });

        function init_small_summernote(component_container) {
            component_container.find('textarea').each(function () {
                if ($(this).hasClass('summernote_init') && $(this).is(':visible')) {
                    $(this).summernote(summernote_options);
                }
            });
        }

        $(document).on('click', '.accordion-add-btn-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.accordion-btn-list-template');
            const container = $(this).parent().find('.accordion-btn-list-container');

            create_item(template, container, '#btnInputPlaceholder');

            container.find('input, textarea').prop('disabled', false);

            container.find('.btn-icon-select2').each(function () {
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
