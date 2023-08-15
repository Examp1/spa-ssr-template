<script type="text/javascript">
    (function () {
        $('.select2-internal-init').each(function () {
            $(this).select2({});
        });

        $(document).on('click', '.add-see-also-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.see-also-list-template');
            const container = $(this).parent().find('.see-also-list-container');

            create_item(template, container, '#dynamicListPlaceholder');

            container.find('input, textarea').prop('disabled', false);

            container.find('.select2-internal').each(function () {
                $(this).select2({});
            });
        });

        $(document).on('click', '.remove-item', function () {
            $(this).parents('.item-group').remove();
        });

        /* INTERLINK component */
        $(document).on('change', '.interlink-select-type-{{$lang}}', function () {
            let type = $(this).val();
            console.log(type)
            $(this).siblings('.select-type').hide();
            $(this).siblings('.select-type-' + type).show();
        });
    })();
</script>
