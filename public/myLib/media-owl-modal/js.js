$(document).ready(function () {
    $(document).on('click', '.owl-modal-close-btn', function () {
        $(this).closest('.owl-modal-container').remove();
    });

    $(document).on('click', '.owl-modal-save-alt-btn', function () {
        let id = $(this).data('id');
        let val = $(".owl-modal-alt-input[data-id='" + id + "']").val();

        let media_class = "data-id-" + id;

        $(".media-wrapper." + media_class).find('.media-input-alt').val(val);

        $(".media-wrapper." + media_class).removeClass(media_class);

        $(this).closest('.owl-modal-container').remove();
    });
})
