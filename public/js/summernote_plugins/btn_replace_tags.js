function getSelectionHtml() {
    var html = "";
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            html = container.innerHTML;
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            html = document.selection.createRange().htmlText;
        }
    }

    return html;
}

function ReplaceButtonTags(context) {
    var ui = $.summernote.ui;
    var button = ui.button({
        contents: '<i class="fa fa-window-close"/>',
        tooltip: 'Очистити форматування',
        click: function (e) {
            let html = getSelectionHtml();

            html = html.replaceAll("&nbsp;", " ");

            html = html.replaceAll(/(<[^>]+) style=".*?"/i, '');
            html = html.replaceAll(/(<[^>]+) class=".*?"/i, '');

            let description = '';

            try {
                description = $(html).text();
            } catch (e) {
                html = "<p>" + html + "</p>";
                description = $(html).text();
            }

            var $html = $.parseHTML(html);

            for (let i = 0; i < $html.length; i++) {
                try {
                    let tagName = $html[i].tagName.toLowerCase();

                    switch (tagName) {
                        case 'div':
                            // description = description.replace($html[i].innerText, $html[i].innerText);
                            break;
                        case 'p':
                            description = description.replace($html[i].innerText, "<p>" + $html[i].innerText + "</p>");
                            break;
                        case 'h1':
                            description = description.replace($html[i].innerText, "<h1>" + $html[i].innerText + "</h1>");
                            break;
                        case 'h2':
                            description = description.replace($html[i].innerText, "<h2>" + $html[i].innerText + "</h2>");
                            break;
                        case 'h3':
                            description = description.replace($html[i].innerText, "<h3>" + $html[i].innerText + "</h3>");
                            break;
                        case 'h4':
                            description = description.replace($html[i].innerText, "<h4>" + $html[i].innerText + "</h4>");
                            break;
                        case 'h5':
                            description = description.replace($html[i].innerText, "<h5>" + $html[i].innerText + "</h5>");
                            break;
                        case 'h6':
                            description = description.replace($html[i].innerText, "<h6>" + $html[i].innerText + "</h6>");
                            break;

                    }
                } catch (e) {}
            }

            //  Удаляет только в середине текста
            description = description.replaceAll("<p></p>","");

            document.execCommand('insertHtml', false, description);
        }
    });

    return button.render();
}
