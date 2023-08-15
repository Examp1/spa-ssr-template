// function ReplaceButton(context) {
//     var ui = $.summernote.ui;
//     var button = ui.button({
//         contents: '<i class="fa fa-quote-right"/> Типографіка',
//         tooltip: 'Типографіка',
//         click: function() {
//             let text = '';
//             let rng = context.$note.summernote('getLastRange');
//
//             if(rng.sc.nodeName === '#text'){
//                 console.log('nodeName = #text');
//                 text = rng.sc.nodeValue;
//                 console.log('text = ',text,rng);
//             }
//
//             if(rng.sc.nodeName === 'P'){
//                 console.log('nodeName = P',rng.sc);
//                 text = rng.sc.innerText;
//                 console.log('text = ',text);
//             }
//
//             if(! text){
//                 console.log('not selected range');
//                 return;
//             }
//
//             let selectedText = text.slice(rng.so, rng.eo);
//
//             if(! selectedText){
//                 console.log('not selected text');
//                 return;
//             }
//
//             let newText = selectedText.replace('-','—');
//             newText = newText.replace('–','—');
//
//             let firstS = newText.substr(0, 1);
//             let middleS = newText.substr(1, newText.length - 2);
//             let lastS = newText.substr(newText.length - 1, 1);
//
//             if(firstS === '\'' && lastS === '\''){
//                 newText = '«'+middleS+'»';
//             }
//
//             if(firstS === '\"' && lastS === '\"'){
//                 newText = '«'+middleS+'»';
//             }
//
//             if(selectedText !== newText){
//                 context.invoke('editor.insertText', newText);
//             }
//         }
//     });
//
//     return button.render();
// }

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

function isHTML(str) {
    var a = document.createElement('div');
    a.innerHTML = str;

    for (var c = a.childNodes, i = c.length; i--;) {
        if (c[i].nodeType == 1) return true;
    }

    return false;
}

function ReplaceButton(context) {
    var ui = $.summernote.ui;
    var button = ui.button({
        contents: '<i class="fa fa-quote-right"/> Типографіка',
        tooltip: 'Типографіка',
        click: function (e) {
            let html = getSelectionHtml();

            if (isHTML(html)) {
                let arr = [];

                html.match(/>(.*?)</g).map(function (val) {
                    let str = val.substring(1, val.length - 1);

                    if (str) {
                        let newStr = str.replace('-', '—');
                        newStr = newStr.replace('–', '—');

                        try {
                            newStr.match(/'(.*?)'/g).map(function (val2) {
                                let firstS = val2.substr(0, 1);
                                let middleS = val2.substr(1, val2.length - 2);
                                let lastS = val2.substr(val2.length - 1, 1);

                                if (firstS === '\'' && lastS === '\'') {
                                    newStr = newStr.replace(val2, '«' + middleS + '»');
                                }
                            });
                        } catch (e) { }

                        try {
                            newStr.match(/"(.*?)"/g).map(function (val3) {
                                console.log(val3);
                                let firstS = val3.substr(0, 1);
                                let middleS = val3.substr(1, val3.length - 2);
                                let lastS = val3.substr(val3.length - 1, 1);

                                if (firstS === '\"' && lastS === '\"') {
                                    newStr = newStr.replace(val3, '«' + middleS + '»');
                                }
                            });
                        } catch (e) { }

                        newStr = '>' + newStr + '<';

                        arr.push({
                            str: val,
                            newStr: newStr,
                        });
                    }
                });

                for (let i = 0; i < arr.length; i++) {
                    html = html.replace(arr[i].str, arr[i].newStr);
                }
            } else {
                html = html.replace('-', '—');
                html = html.replace('–', '—');

                try {
                    html.match(/'(.*?)'/g).map(function (val2) {
                        let firstS = val2.substr(0, 1);
                        let middleS = val2.substr(1, val2.length - 2);
                        let lastS = val2.substr(val2.length - 1, 1);

                        if (firstS === '\'' && lastS === '\'') {
                            html = html.replace(val2, '«' + middleS + '»');
                        }
                    });
                } catch (e) { }

                try {
                    html.match(/"(.*?)"/g).map(function (val3) {
                        console.log(val3);
                        let firstS = val3.substr(0, 1);
                        let middleS = val3.substr(1, val3.length - 2);
                        let lastS = val3.substr(val3.length - 1, 1);

                        if (firstS === '\"' && lastS === '\"') {
                            html = html.replace(val3, '«' + middleS + '»');
                        }
                    });
                } catch (e) { }
            }

            document.execCommand('insertHtml', false, html);
        }
    });

    return button.render();
}
