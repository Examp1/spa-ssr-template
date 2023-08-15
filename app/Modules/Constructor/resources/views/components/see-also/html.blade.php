@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    <div class="row">
        <div class="input-group">
            <div style="display: none;">
                <div data-item-id="#dynamicListPlaceholder" class="see-also-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                    <div class="col-10">
                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#dynamicListPlaceholder][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" disabled>

                        @include('admin.pieces.fields.interlink',[
                            'lang' => $lang,
                            'name_type' => constructor_field_name($key, 'content.list') .'[#dynamicListPlaceholder][interlink_type]',
                            'name_val' => constructor_field_name($key, 'content.list') .'[#dynamicListPlaceholder][interlink_val]',
                        ])
                    </div>

                    <div class="col-2">
                        <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

            <div class="see-also-list-container w-100">
                @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                    <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                        <div class="col-10">
                            <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][title]" placeholder="{{ $params['labels']['title'] }}" class="form-control mt-3 mb-1" value="{{ $value['title'] ?? '' }}">

                            <?php
                                $interlinkSelType = $value['interlink_type'] ?? '';
                                $interlinkSelVal =  $value['interlink_val'] ?? '';
                            ?>
                            @include('admin.pieces.fields.interlink',[
                                'lang' => $lang,
                                'name_type' => constructor_field_name($key, 'content.list'). '[' . $k . '][interlink_type]',
                                'name_val' => constructor_field_name($key, 'content.list'). '[' . $k . '][interlink_val]',
                                'sel_type' => $interlinkSelType,
                                'sel_val' => $interlinkSelVal,
                            ])
                        </div>

                        <div class="col-2">
                            <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="button" class="btn btn-info btn-sm add-see-also-list-item_{{$lang}} d-block mt-2">Добавить</button>
    </div>
</div>

@include('constructor::layouts.footer')
