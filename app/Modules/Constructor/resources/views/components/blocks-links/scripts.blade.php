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

        $(document).on('click', '.add-blocks-links-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.blocks-links-list-template');
            const container = $(this).parent().find('.blocks-links-list-container');

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

        $(document).on('click', '.blocks-links-add-btn-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.blocks-links-btn-list-template');
            const container = $(this).parent().find('.blocks-links-btn-list-container');

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

        $(document).on("click",".add-links_{{$lang}}", function(){
            let addedCount = $(this).siblings('input').val();

            if(addedCount == 4) return;

            if(addedCount == ""){
                addedCount = 1;
            } else {
                addedCount++;
            }

            $(this).siblings('.link-blocks').find('.link-b').hide();

            if(addedCount == 1){
                $(this).siblings('.link-blocks').find('.link-b.link-b-1').show();
            } else if(addedCount == 2){
                $(this).siblings('.link-blocks').find('.link-b.link-b-1').show();
                $(this).siblings('.link-blocks').find('.link-b.link-b-2').show();
            } else if(addedCount == 3){
                $(this).siblings('.link-blocks').find('.link-b.link-b-1').show();
                $(this).siblings('.link-blocks').find('.link-b.link-b-2').show();
                $(this).siblings('.link-blocks').find('.link-b.link-b-3').show();
            } else if(addedCount == 4){
                $(this).siblings('.link-blocks').find('.link-b.link-b-1').show();
                $(this).siblings('.link-blocks').find('.link-b.link-b-2').show();
                $(this).siblings('.link-blocks').find('.link-b.link-b-3').show();
                $(this).siblings('.link-blocks').find('.link-b.link-b-4').show();
            }

            $(this).siblings('input').val(addedCount);
        })
    })();
</script>
