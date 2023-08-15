<script type="text/javascript">
    (function () {
        $(document).on('click', '.add-partners-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.partners-list-template');
            const container = $(this).parent().find('.partners-list-container');

            create_item(template, container, '#imageInputPlaceholder1');

            container.find('input, textarea').prop('disabled', false);

            $(".partners_sortable_{{$lang}}").sortable({
                update: function (event, ui) {
                    const sortable_array = $(this).sortable('toArray');
                    for(let i=0;i<sortable_array.length;i++){
                        $(".partners_sortable_{{$lang}}").find("#"+sortable_array[i]).find('.sort_sort').val(i);
                    }
                }
            });
            $( ".partners_sortable_{{$lang}}, .ui-state-item-no-select" ).disableSelection();
        });

        $(document).on('click', '.remove-item', function () {
            $(this).parents('.item-group').remove();
        });

        $(".partners_sortable_{{$lang}}").sortable({
            update: function (event, ui) {
                const sortable_array = $(this).sortable('toArray');
                for(let i=0;i<sortable_array.length;i++){
                    $(".partners_sortable_{{$lang}}").find("#"+sortable_array[i]).find('.sort_sort').val(i);
                }
            }
        });
        $( ".partners_sortable_{{$lang}}, .ui-state-item-no-select" ).disableSelection();

        $(document).on('click', '.partners-add-btn-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.partners-btn-list-template');
            const container = $(this).parent().find('.partners-btn-list-container');

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
