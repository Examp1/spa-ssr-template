class imExportTableLib {
    constructor(obj) {
        this.$card = $(obj.cardQuerySelector)
        this.$table = this.$card.find('table');
        this.dbTable = obj.dbTable;
        this.init();
    }

    init() {
        $('body').append(this.renderModal($("meta[name='csrf-token']").attr('content')));
        let $cardHeader = this.$card.find('.card-header');

        if($cardHeader.length == 0) {
            this.$card.prepend("<div class='card-header'></div>");
            $cardHeader = this.$card.find('.card-header');
        }

        $cardHeader.append(this.renderBtn());

        let $modal = $("#exportSettingModal");
        this.$table.find('thead tr th').each(function(){
            if($(this).data('field')){
                let fieldName = $(this).data('field');
                let fieldTitle = $(this).text();
                let checkElem = '' +
                    '<div class="mb-3 form-check">\n' +
                    '<input type="checkbox" class="form-check-input" checked id="'+fieldName+'" value="'+fieldName+'" name="fields[]">\n' +
                    '<label class="form-check-label" for="'+fieldName+'">'+fieldTitle+'</label>\n' +
                    '</div>';
                $modal.find('.modal-body').append(checkElem);
            }
        });

        $modal.find('input[name="table"]').val(this.dbTable);

        $cardHeader.find('.exportable-btn-group ul li').each(function(){
            if($(this).data('aggregator')){
                $(this).find('a').on('click',function () {
                    $modal.find('input[name="aggregator"]').val($(this).parent('li').data('aggregator'));
                    $modal.find('input[type="submit"]').trigger('click');
                });
            }
        });
    }

    renderBtn() {
        let btn = '<div class="btn-group exportable-btn-group float-right">\n' +
            '                    <button type="button" class="btn btn-success dropdown-toggl text-white" data-toggle="dropdown" aria-expanded="false">\n' +
            '                        Експорт <i class="mdi mdi-export"></i>\n' +
            '                    </button>\n' +
            '                    <ul class="dropdown-menu">\n' +
            '                        <li data-aggregator="xlsx">\n' +
            '                            <a class="dropdown-item" href="javascript:void(0)">\n' +
            '                                <i class="mdi mdi-file-excel-box"></i>\n' +
            '                                Excel\n' +
            '                            </a>\n' +
            '                        </li>\n' +
            '                        <li data-aggregator="csv">\n' +
            '                            <a class="dropdown-item" href="javascript:void(0)">\n' +
            '                                <i class="mdi mdi-file-excel-box"></i>\n' +
            '                                CSV\n' +
            '                            </a>\n' +
            '                        </li>\n' +
            '                        <li><hr class="dropdown-divider"></li>\n' +
            '                        <li>\n' +
            '                            <a class="dropdown-item" data-toggle="modal" data-target="#exportSettingModal" href="javascript:void(0)">\n' +
            '                                <i class="mdi mdi-settings"></i>\n' +
            '                                Налаштування\n' +
            '                            </a>\n' +
            '                        </li>\n' +
            '                    </ul>\n' +
            '                </div>';


        return btn;
    }

    renderModal(token) {
        let modal = '<div class="modal fade" id="exportSettingModal" tabindex="-1" aria-hidden="true">\n' +
            '        <form action="/admin/export-table" method="post">\n' +
            '            <input type="hidden" name="_token" value="'+token+'">\n' +
            '            <input type="hidden" name="aggregator" value="xlsx">\n' +
            '            <input type="hidden" name="table">\n' +
            '        <div class="modal-dialog">\n' +
            '            <div class="modal-content">\n' +
            '                <div class="modal-header">\n' +
            '                    <h5 class="modal-title" id="exampleModalLabel">Налаштування полів експорту</h5>\n' +
            '                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>\n' +
            '                </div>\n' +
            '                <div class="modal-body"></div>\n' +
            '                <div class="modal-footer">\n' +
            '                    <button type="button" data-dismiss="modal" class="btn btn-primary">Зберегти</button>\n' +
            '                    <input type="submit" class="btn btn-success text-white" value="Експортувати">\n' +
            '                </div>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '        </form>\n' +
            '    </div>';

        return modal;
    }

}
