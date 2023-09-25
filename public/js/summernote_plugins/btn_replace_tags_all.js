function ReplaceButtonTagsAll(context) {
    var ui = $.summernote.ui;
    var button = ui.button({
        contents: '<i class="fa fa-code"/> Очистити все',
        tooltip: 'Очистити все форматування',
        click: function (e) {
            let text = $(context.$note.summernote('code')).text();

            context.$note.summernote("code", text);
        }
    });

    return button.render();
}
