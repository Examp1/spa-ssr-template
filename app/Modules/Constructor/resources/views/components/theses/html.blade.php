@include('constructor::layouts.header',['lang' => $lang])

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang,'btns_hide' => true])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="input-group">
                    <div style="display: none;">
                        <div data-item-id="#imageInputPlaceholder1" class="theses-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                            <div class="col-10">
                                <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][item]" placeholder="{{ $params['labels']['item'] }}" class="form-control mt-3 mb-1" disabled>
                                <select class="form-control btn-icon-select2" style="width: 150px" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][icon]">
                                    @foreach(config('buttons.icon') as $listKey => $listItem)
                                        <option value="{{ $listKey }}" data-icon="{{$listKey}}">{{ $listItem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="theses-list-container w-100">
                        @foreach((array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []) as $k => $value)
                            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center">
                                <div class="col-10">
                                    <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][item]" placeholder="{{ $params['labels']['item'] }}" class="form-control mt-3 mb-1" value="{{ $value['item'] ?? '' }}">
                                    <?php $icon = $value['icon'] ?? ''; ?>
                                    <select class="form-control btn-icon-select2-ready" style="width: 150px" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][icon]">
                                        @foreach(config('buttons.icon') as $listKey => $listItem)
                                            <option value="{{ $listKey }}" @if($icon == $listKey) selected @endif data-icon="{{$listKey}}">{{ $listItem }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="btn btn-success text-white btn-sm add-theses-list-item_{{$lang}} d-block mt-2">Додати</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
