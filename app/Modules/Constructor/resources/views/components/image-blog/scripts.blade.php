<script type="text/javascript">
    (function () {

        $(document).on('click', '.full-image-add-btn-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.full-image-btn-list-template');
            const container = $(this).parent().find('.full-image-btn-list-container');

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
