<script type="text/javascript">
    (function () {
        $(".summernote_initialized").each(function () {
            $(this).summernote(summernote_options);
        });

        setTimeout(function () {
            $('.select2-internal-init').each(function () {
                $(this).select2({});
            });
        },1000)

        $(document).on('click', '.add-blocks-slider-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.blocks-slider-list-template');
            const container = $(this).parent().find('.blocks-slider-list-container');

            create_item(template, container, '#imageInputPlaceholder1');

            container.find('input, textarea').prop('disabled', false);

            init_small_summernote(container);

            container.find('.select2-internal').each(function () {
                $(this).select2({});
            });
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

        /* INTERLINK component */
        $(document).on('change', '.interlink-select-type-{{$lang}}', function () {
            let type = $(this).val();
            console.log(type)
            $(this).siblings('.select-type').hide();
            $(this).siblings('.select-type-' + type).show();
        });

        $(document).on('click', '.blocks-slider-add-btn-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.blocks-slider-btn-list-template');
            const container = $(this).parent().find('.blocks-slider-btn-list-container');

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
