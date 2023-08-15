function getYoutubeId(string) {
    if (!string) return false;
    var matches = string.match(/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/);
    return matches && matches[2].length === 11 ? matches[2] : false;
}

function getVimeoId(string) {
    var matches = /vimeo.*\/(\d+)/i.exec( string );
    return matches && matches[1].length ? matches[1] : false;
}

function getInstagramId(string) {
    var matches = /(p|tv)\/(.*?)\//.exec(string);

    return matches && matches[2].length ? matches[2] : false;
}

function VideoButton(context) {
    var ui = $.summernote.ui;
    var button = ui.button({
        contents: '<i class="fa fa-video-camera"/>',
        tooltip: 'Вставить видео по ссылке',
        click: function() {
            let $dialog = $("<div title='Вставить видео' id='video_dialog'><div class=\"form-group note-form-group row-fluid\"><label for=\"note-dialog-video-url-16335170730761\" class=\"note-form-label\">URL видео <small class=\"text-muted\">(YouTube, Vimeo, Instagram)</small></label><input id=\"note_dialog_video_url\" class=\"note-video-url form-control note-form-control note-input\" type=\"text\"></div><input type=\"button\" href=\"#\" class=\"btn btn-primary note-btn note-btn-primary note-video-btn float-right\" value=\"Вставить видео\"></div>");
            $('body').append($dialog);

            $dialog.dialog({
                width: 500,
                position: {
                    my: "center top",
                    at: ("center top+"+(window.innerHeight*.1)),
                    collision: "none"
                },
                close: function( event, ui ) {
                    $(document).find("#video_dialog").remove();
                }
            });

            $(".note-video-btn").off('click');
            $(".note-video-btn").on('click',function () {

                let url = $("#note_dialog_video_url").val();

                // check url for youtube
                if(getYoutubeId(url)){
                    url = "//www.youtube.com/embed/" + getYoutubeId(url);
                }

                // check url for vimeo
                if(getVimeoId(url)){
                    console.log(getVimeoId(url));
                    url = "//player.vimeo.com/video/" + getVimeoId(url);
                }

                // check url for instagram
                if(getInstagramId(url)){
                    url = "https://instagram.com/p/"+getInstagramId(url)+"/embed/";
                }

                $(document).find("#video_dialog").remove();

                var iframe = document.createElement('iframe');

                iframe.src = url;
                iframe.setAttribute('frameborder', 0);
                iframe.setAttribute('width', '560');
                iframe.setAttribute('height', '314');
                iframe.setAttribute('allowfullscreen', true);
                //
                let rng = context.$note.summernote('getLastRange');
                //
                rng.insertNode(iframe);

                // let code = context.$note.summernote('code');
                //
                // //
                // context.$note.summernote('code','');
                // context.invoke('pasteHTML', code);
                context.$note.summernote('undo');
                context.$note.summernote('redo');
            });
        }
    });

    return button.render();
}
