<script type="text/javascript">
    (function() {
        $(document).on('click', '.add-blogTable-row_{{ $lang }}', function() {
            let $countRowComponent = $(this).closest(".card-body").find(".row-count-component");
            let $styleTypeComponent = $(this).closest(".card-body").find(".row-style-type-component");
            let $styleTypeContainer = $(this).closest(".card-body").find(".row-icon-select2-container");
            let styleTypeVal = $styleTypeComponent.val();

            if (styleTypeVal == "icons") {
                $styleTypeContainer.css("display", "flex");
            } else {
                $styleTypeContainer.css("display", "none");
            }

            const count = $(this).parent().find('.item-row-template-count>option:selected').val();
            const template = $(this).parent().find('.item-row-template' + count);
            const container = $(this).parent().find('.items-row-container');

            create_item(template, container, '#blogTableRowPlaceholder' + count);

            container.find('input').prop('disabled', false);

            if (styleTypeVal == "icons") {
                container.find('.row-icon-select2').each(function() {
                    $(this).select2({
                        minimumResultsForSearch: -1,
                        templateResult: formatStateIcon,
                        templateSelection: formatStateIcon,
                    });
                });
            }

            // $countRowComponent.prop("disabled",true);
            // $styleTypeComponent.prop("disabled",true);
        });

        //Mob
        $(document).on('click', '.add-blogTable-mob_row_{{ $lang }}', function() {
            let $countRowComponent = $(this).closest(".card-body").find(".mob_row-count-component");
            let $styleTypeComponent = $(this).closest(".card-body").find(".mob_row-style-type-component");
            let $styleTypeContainer = $(this).closest(".card-body").find(".mob_row-icon-select2-container");
            let styleTypeVal = $styleTypeComponent.val();

            if (styleTypeVal == "icons") {
                $styleTypeContainer.css("display", "flex");
            } else {
                $styleTypeContainer.css("display", "none");
            }

            const count = $(this).parent().find('.item-mob_row-template-count>option:selected').val();
            const template = $(this).parent().find('.item-mob_row-template' + count);
            const container = $(this).parent().find('.items-mob_row-container');

            console.log(container);

            create_item(template, container, '#blogTableRowPlaceholder' + count);

            container.find('input').prop('disabled', false);

            if (styleTypeVal == "icons") {
                container.find('.mob_row-icon-select2').each(function() {
                    $(this).select2({
                        minimumResultsForSearch: -1,
                        templateResult: formatStateIcon,
                        templateSelection: formatStateIcon,
                    });
                });
            }
        });

        $(document).on('click', '.edit-item', function() {
            console.log($(this).parent().parent());
            init_small_summernote($(this).parent().parent());
        });

        $(document).on('click', '.edit-item-del', function() {
            console.log($(this).parent().parent());
            del_small_summernote($(this).parent().parent());
        });

        $(document).on('click', '.remove-row-item', function() {
            $(this).parents('.item-group').remove();
        });

        function init_small_summernote(component_container) {
            component_container.find('textarea').each(function() {
                if ($(this).hasClass('small_summernote') && $(this).is(':visible')) {
                    $(this).summernote({
                        height: 250,
                        minHeight: null,
                        maxHeight: null,
                        maxWidth: '100%',
                        lang: 'uk-UA',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'italic', 'clear']],
                            ['fontname', ['fontname']],
                            // ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            // ['table', ['table']],
                            ['insert', ['link', /*'picture', 'video'*/ ]],
                            ['view', ['fullscreen', 'codeview']],
                        ],
                    });
                }
            });
        }

        function del_small_summernote(component_container) {
            component_container.find('textarea').each(function() {
                if ($(this).hasClass('small_summernote')) {
                    $(this).summernote('destroy');
                }
            });
        }

        $(document).ready(function() {
            setTimeout(function() {
                $(".row-icon-select2-ready").each(function() {
                    $(this).select2({
                        templateResult: formatStateIcon,
                        templateSelection: formatStateIcon,
                    });
                });
            }, 500);
        });
    })();
</script>
