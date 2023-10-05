const MoreButton = function (context) {
    const ui = $.summernote.ui;
    const button = ui.button({
        contents: 'Читати далі',
        tooltip: 'Читати далі',
        click: function () {
            context.invoke('editor.pasteHTML', '<span class="hideMarker" style="color:red">сховати весь текст внизу</span>');
        }
    });

    return button.render();
};
