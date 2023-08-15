<script type="text/javascript">
    (function () {
        $(document).on('click',".select-file-btn",function () {
            window.open('/filemanager?type=file', 'FileManager', 'width=900,height=600');
            window.SetUrl = function( url ) {
                if(url.length){
                    try {
                        let urlParts = url[0].url.split('files');
                        if(urlParts.length == 2){
                            $(".file-path-field").val(urlParts[1]);
                        }
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            };
        });

        $(document).on('click', '.video-and-text-add-btn-list-item_{{$lang}}', function () {
            const template = $(this).parent().find('.video-and-text-btn-list-template');
            const container = $(this).parent().find('.video-and-text-btn-list-container');

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
