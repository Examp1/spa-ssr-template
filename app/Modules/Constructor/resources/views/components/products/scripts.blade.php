<script type="text/javascript">
    (function () {
        $(document).on('click', '.add-product-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.product-list-template');
            const container = $(this).parent().find('.product-list-container');

            create_item(template, container, '#imageInputPlaceholder1');

            container.find('input, textarea').prop('disabled', false);

            container.find('.select2-field-pr').select2();
        });

        $(document).on('click', '.remove-item', function () {
            $(this).parents('.item-group').remove();
        });
    })();
</script>
