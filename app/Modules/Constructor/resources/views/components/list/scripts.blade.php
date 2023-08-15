<script type="text/javascript">
    (function () {
        $(document).on('click', '.add-list-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.list-list-template');
            const container = $(this).parent().find('.list-list-container');

            create_item(template, container, '#imageInputPlaceholder1');

            container.find('input, textarea').prop('disabled', false);
        });

        $(document).on('click', '.remove-item', function () {
            $(this).parents('.item-group').remove();
        });
    })();
</script>
