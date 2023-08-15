// Define function to open filemanager window
const lfm = function (options, cb) {
    const route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
    window.SetUrl = cb;
};

// Define LFM summernote button
const LFMButton = function (context) {
    const ui = $.summernote.ui;
    const button = ui.button({
        contents: '<i class="note-icon-picture"></i> ',
        tooltip: 'Вставить изображение с файлового менеджера',
        click: function () {
            lfm({type: 'image', prefix: '/filemanager'}, function (lfmItems, path) {
                lfmItems.forEach(function (lfmItem) {
                    context.invoke('insertImage', lfmItem.url);
                });
            });

        }
    });

    return button.render();
};
