<script type="text/javascript">
    (function () {
        $(document).on('click', '.add-team-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.team-list-template');
            const container = $(this).parent().find('.team-list-container');

            create_item(template, container, '#imageInputPlaceholder1');

            container.find('input, textarea').prop('disabled', false);

            init_small_summernote(container);
        });

        $(document).on('click', '.remove-item', function () {
            $(this).parents('.item-group').remove();
        });
    })();

    function init_small_summernote(component_container) {
        component_container.find('textarea').each(function () {
            if ($(this).hasClass('summernote_init') && $(this).is(':visible')) {
                $(this).summernote(summernote_options);
            }
        });
    }
</script>
