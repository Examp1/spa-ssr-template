<script type="text/javascript">
    (function () {
        $(document).on('click', '.add-gallery3-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.gallery3-list-template');
            const container = $(this).parent().find('.gallery3-list-container');

            create_item(template, container, '#imageInputPlaceholder1');

            container.find('input, textarea').prop('disabled', false);

            let eCount = $(this).closest('.card-body').find(".gallery3-list-container-{{$lang}}").find('.gallery3-list-template').length;
            if(eCount >= 3){
                $(this).css('visibility','hidden');
            } else {
                $(this).css('visibility','visible');
            }
        });

        $(document).on('click', '.gallery3-remove-item_{{$lang}}', function () {
            let cardBody = $(this).closest('.card-body');
            $(this).parents('.item-group').remove();

            let eCount = cardBody.find(".gallery3-list-container-{{$lang}}").find('.gallery3-list-template').length;
            if(eCount >= 3){
                cardBody.find(".add-gallery3-list-item_{{$lang}}").css('visibility','hidden');
            } else {
                cardBody.find(".add-gallery3-list-item_{{$lang}}").css('visibility','visible');
            }
        });

        $(document).on('click', '.images3-add-btn-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.images3-btn-list-template');
            const container = $(this).parent().find('.images3-btn-list-container');

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
    })();
</script>
