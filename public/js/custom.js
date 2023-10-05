function insertParam(key, value, url = null) {
    key = encodeURIComponent(key);
    value = encodeURIComponent(value);

    if (url) {
        var kvp = url.substr(1).split('&');
    } else {
        var kvp = document.location.search.substr(1).split('&');
    }
    let i = 0;

    for (; i < kvp.length; i++) {
        if (kvp[i].startsWith(key + '=')) {
            let pair = kvp[i].split('=');
            pair[1] = value;
            kvp[i] = pair.join('=');
            break;
        }
    }

    if (i >= kvp.length) {
        kvp[kvp.length] = [key, value].join('=');
    }

    // can return this or...
    let params = kvp.join('&');

    return params;
}


String.prototype.replace_all = function (search, replace) {
    return this.split(search).join(replace);
};

function create_item(template, container, placeholder) {
    const cloneItem = template
        .clone()[0]
        .outerHTML
        .replace_all(placeholder, get_item_id(container.children()));

    container.append(cloneItem);
}

function create_item_from_template(template, container, placeholder) {
    const cloneItem = template
        .html()
        .replace_all(placeholder, get_item_id(container.children()));

    container.append(cloneItem);
}

function get_item_id(items) {
    if (items.length > 0) {
        const array_items = [];

        for (let i = 0; i < items.length; i++) {
            let property_value = +jQuery(items[i]).attr('data-item-id');
            array_items.push(property_value);
        }

        return Math.max.apply(null, array_items) + 1;
    }

    return 1;
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
}

function translit(word) {
    var answer = '';
    var converter = {
        'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd',
        'е': 'e', 'ё': 'e', 'ж': 'zh', 'з': 'z', 'и': 'i',
        'й': 'y', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
        'о': 'o', 'п': 'p', 'р': 'r', 'с': 's', 'т': 't',
        'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c', 'ч': 'ch',
        'ш': 'sh', 'щ': 'sch', 'ь': '', 'ы': 'y', 'ъ': '',
        'э': 'e', 'ю': 'yu', 'я': 'ya',

        'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D',
        'Е': 'E', 'Ё': 'E', 'Ж': 'Zh', 'З': 'Z', 'И': 'I',
        'Й': 'Y', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N',
        'О': 'O', 'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T',
        'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C', 'Ч': 'Ch',
        'Ш': 'Sh', 'Щ': 'Sch', 'Ь': '', 'Ы': 'Y', 'Ъ': '',
        'Э': 'E', 'Ю': 'Yu', 'Я': 'Ya', ' ': '-'
    };

    for (var i = 0; i < word.length; ++i) {
        if (converter[word[i]] == undefined) {
            answer += word[i].toLowerCase();
        } else {
            answer += converter[word[i]].toLowerCase();
        }
    }

    return answer;
}

$(document).ready(function () {
    /* SORTING*/
    $(document).on('click', "table.imSortingTableLib th.sorting", function () {
        $("table.imSortingTableLib th.sorting").removeClass('sorting_asc');
        $("table.imSortingTableLib th.sorting").removeClass('sorting_desc');
        $(this).addClass('sorting_asc');

        let field = $(this).data('field');
        let url = insertParam('sort', field);
        url = insertParam('order', 'asc', '?' + url);

        document.location.search = url;
    });

    $(document).on('click', "table.imSortingTableLib th.sorting_asc", function () {
        $("table.imSortingTableLib th.sorting").removeClass('sorting_asc');
        $("table.imSortingTableLib th.sorting").removeClass('sorting_desc');
        $(this).addClass('sorting_desc');

        let field = $(this).data('field');
        let url = insertParam('sort', field);
        url = insertParam('order', 'desc', '?' + url);

        document.location.search = url;
    });

    $(document).on('click', "table.imSortingTableLib th.sorting_desc", function () {
        $("table.imSortingTableLib th.sorting").removeClass('sorting_asc');
        $("table.imSortingTableLib th.sorting").removeClass('sorting_desc');
        $(this).addClass('sorting_asc');

        let field = $(this).data('field');
        let url = insertParam('sort', field);
        url = insertParam('order', 'asc', '?' + url);

        document.location.search = url;
    });

    $(".save-btn").on("click", function () {
        $("input[name='save_method']").val('save_and_back');
        $(this).closest('form').find('input[type="submit"]').trigger('click');
    });

    $(".cancel-btn").on("click", function () {
        let url = $(this).data('url');
        Swal.fire({
            title: 'Ви впевнені?',
            text: "Ви дійсно хочете скасувати дію?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Так!',
            cancelButtonText: 'Ні!'
        }).then((result) => {
            if (result.value) {
                window.location.href = url;
            }
        })
    });

    $(".meta-field").on('input', function () {
        let lang = $(this).data('lang');
        $(".meta_created_as_" + lang).val(1)
    });

    $(".meta-field").on('blur', function () {
        let lang = $(this).data('lang');
        let isChanged = $(".meta_created_as_" + lang).val();
        let isAutoGen = $(".meta_auto_gen_" + lang).prop('checked');

        // Если мета данные изменяли вручную
        if (isChanged == '1' && isAutoGen) {
            Swal.fire({
                title: '',
                text: "Meta дані були змінені вручну, вимкнути автогенерацію?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Так!',
                cancelButtonText: 'Ні!'
            }).then((result) => {
                if (result.value) {
                    $(".meta_auto_gen_" + lang).prop('checked', false);
                }
            });
        }
    });

    $('.delete-item-btn').on('click', function () {
        let _this = $(this);
        Swal.fire({
            title: 'Ви впевнені?',
            text: "Ви справді хочете видалити запис?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Так!',
            cancelButtonText: 'Ні!'
        }).then((result) => {
            if (result.value) {
                _this.closest('form').submit();
            }
        });
    });

    $('.check-all').on('change', function () {
        if ($(this).prop('checked')) {
            $('.checkbox-item').prop('checked', true);
        } else {
            $('.checkbox-item').prop('checked', false);
        }
    });

    $('.checkbox-item').on('change', function () {
        let checked = 0;
        $('.checkbox-item').each(function () {
            if ($(this).prop('checked')) {
                checked++;
            }
        });

        if ($('.checkbox-item').length === checked) {
            $('.check-all').prop('checked', true)
        } else {
            $('.check-all').prop('checked', false)
        }
    });

    $(".btn-delete-checked").on('click', function () {
        let checked = [];

        $('.checkbox-item').each(function () {
            if ($(this).prop('checked')) {
                checked.push($(this).val());
            }
        });

        if (checked.length == 0) {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'Немає вибраних записів',
                showConfirmButton: false,
                timer: 1500
            })
        } else {
            Swal.fire({
                title: 'Ви впевнені?',
                text: "Ви намагаєтеся видалити записи!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Так, зробити це!',
                cancelButtonText: 'Ні'
            }).then((result) => {
                if (result.value) {
                    $('#delete_sel_form').find('input[name="ids"]').val(JSON.stringify(checked));
                    $('#delete_sel_form').submit();
                }
            });
        }
    });

    $(".btn-update-checked").on('click', function () {
        let checked = [];

        $('.checkbox-item').each(function () {
            if ($(this).prop('checked')) {
                checked.push($(this).val());
            }
        });

        if (checked.length == 0) {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'Немає вибраних записів',
                showConfirmButton: false,
                timer: 1500
            })
        } else {
            Swal.fire({
                title: 'Ви впевнені?',
                text: "Ви намагаєтеся оновити записи!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Так, зробити це!',
                cancelButtonText: 'Ні'
            }).then((result) => {
                if (result.value) {
                    $('#update_sel_form').find('input[name="ids"]').val(JSON.stringify(checked));
                    $('#update_sel_form').find('input[name="status_id"]').val($('#status_change').find(':selected').val());
                    $('#update_sel_form').submit();
                }
            });
        }
    });

    $('.delete-model-btn').on('click', function () {
        Swal.fire({
            title: 'Ви впевнені?',
            text: 'Ви впевнені, що хочете видалити?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Так!',
            cancelButtonText: 'Ні!'
        }).then((result) => {
            if (result.value) {
                $(".delete-model-form").submit();
            }
        });
    });

    $(".nav-main-tab li a").on("click", function () {
        let lang = $(this).data('lang');
        $(".image-language-section").addClass('hide');
        $(".image-language-section.image-language-section-" + lang).removeClass('hide');
    });

    $(".order-plus-btn").on("click", function () {
        let $orderInput = $(this).closest(".inputWrapperOrder").find('input[name="order"]');
        $orderInput.val(parseInt($orderInput.val()) + 1);
    });

    $(".order-minus-btn").on("click", function () {
        let $orderInput = $(this).closest(".inputWrapperOrder").find('input[name="order"]');
        if (parseInt($orderInput.val()) > 0) {
            $orderInput.val(parseInt($orderInput.val()) - 1);
        }
    });

    $(".set-filter-status").on('click', function () {
        let status = $(this).data('status');

        $(this).closest('form').find('input[name="status"]').val(status);
        $(this).closest('form').find('button[type="submit"]').trigger('click');
    });

    // preview btn
    $(document).on("input", ".btn-preview-text", function () {
        $(this).closest(".qwert").find(".btn-preview-block a span").text($(this).val());
    });
    $(document).on("input", ".btn-preview-link", function () {
        $(this).closest(".qwert").find(".btn-preview-block a").attr("href", $(this).val());
    });
    $(document).on("change", ".btn-preview-type", function () {
        let iconClass = $(this).siblings(".btn-preview-icon").val();
        let aClass = "style-btn";


        if (iconClass !== "non") {
            aClass += " hasIcon";
        }

        aClass += " " + $(this).val();

        $(this).closest(".qwert").find(".btn-preview-block a").attr("class", aClass);

        if ($(this).val() === "text") {
            $(this).closest(".qwert").find(".btn-preview-block a i").hide();
        } else {
            $(this).closest(".qwert").find(".btn-preview-block a i").show();
        }
    });
    $(document).on("change", ".btn-preview-icon", function () {
        let iconClass = $(this).val();

        if (iconClass !== "non") {
            $(this).closest(".qwert").find(".btn-preview-block a").addClass("hasIcon");
            $(this).closest(".qwert").find(".btn-preview-block a i").attr("class", "linkIcon " + iconClass);
            $(this).closest(".qwert").find(".btn-preview-block a i").show();
        } else {
            $(this).closest(".qwert").find(".btn-preview-block a").removeClass("hasIcon");
            $(this).closest(".qwert").find(".btn-preview-block a i").attr("class", "");
            $(this).closest(".qwert").find(".btn-preview-block a i").hide();
        }
    });

    $(document).on("change", ".type_link", function () {
        let type = $(this).val();

        if (type == 'form') {
            $(this).siblings('.type_link_link').hide();
            $(this).siblings('.type_link_form').show();
        } else {
            $(this).siblings('.type_link_link').show();
            $(this).siblings('.type_link_form').hide();
        }
    });

    $(document).on("input", ".anker-from-input", function () {
        $(this).siblings('.anker-to-input').val("#" + strSlug($(this).val()));
    });

    $(document).on("click", ".anker-copy-btn", function () {
        $(this).closest('.input-group').find('.anker-to-input').select();
        document.execCommand("copy");
        $(this).closest('.input-group').find('.anker-to-input').blur();
        $.notify("Скопійовано в буфер обміну", {
            className:"success",
            autoHideDelay: 3000,
        });
    });
})

function strSlug(val) {
    return slugify(val, { lower: true, strict: true });
}

const copyBtn = document.querySelector('#copyUrl'),
    urlInput = document.querySelector('#copyInpt');

if (copyBtn) {
    copyBtn.addEventListener('click', (e) => {
        e.preventDefault;
        urlInput.value = window.location.href;
        urlInput.select();
        document.execCommand("copy");
        alert('Url скопійовано в буфер обміну')
    });
}
