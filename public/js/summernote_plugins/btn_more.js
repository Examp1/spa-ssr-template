const MoreButton = function (context) {
    const ui = $.summernote.ui;
    const button = ui.button({
        contents: '<i class="fa fa-minus-square-o"/>',
        tooltip: 'Скрыть текст снизу',
        click: function () {
            context.invoke('editor.pasteHTML', '<span class="hideMarker" style="color:red">скрыть весь текст внизу</span>');
        }
    });

    return button.render();
};
