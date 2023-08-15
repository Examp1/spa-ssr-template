@include('constructor::layouts.header',['lang' => $lang])

<?php
$flag = true;
$listData = (array) old(constructor_field_name($key, 'content.list'), $content['list'] ?? []);

foreach ($listData as $dse){
    if(!isset($dse['order'])){
        $flag = false;
    }
}

if($flag){
    usort($listData, array('App\Service\Adapter', 'cmp_order'));
}

?>

<div id="collapse{{ $key }}_{{$lang}}" class="card-body mt-1 collapse show">
    @include('constructor::pieces.nav-pills',['key' => $key, 'lang' => $lang])
    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills_main_{{ $key }}_{{$lang}}">
            <div class="row">
                <div class="col-12 mb-3 input-group-sm">
                    <div class="form-group input-group-sm mb-12">
                        <input type="text" placeholder="{{ $params['labels']['title'] }}" class="form-control @error(constructor_field_name_dot($key, 'content.title')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.title') }}" value="{{ old(constructor_field_name_dot($key, 'content.title'), $content['title'] ?? '') }}">

                        @error(constructor_field_name_dot($key, 'content.title'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <div style="display: none;">
                        <div class="card item-group link-list-list-template" style="width: 18rem;" data-item-id="#imageInputPlaceholder1">
                            <div class="card-body">
                                <div style="display: flex;gap: 10px">
                                    <div>
                                        {{ file_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][file]') }}
                                    </div>
                                    <div>
                                        {{ media_preview_box(constructor_field_name($key, 'content.list') . '[#imageInputPlaceholder1][icon]') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][name]" placeholder="{{ $params['labels']['name'] }}" class="form-control mt-3 mb-1" disabled>
                                    </div>
                                    <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][link]" placeholder="{{ $params['labels']['link'] }}" class="form-control mt-3 mb-1" disabled>
                                    </div>
                                    <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                        <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][date]" placeholder="{{ $params['labels']['date'] }}" class="form-control mt-3 mb-1" disabled>
                                    </div>
                                    <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                        <input type="number" name="{{ constructor_field_name($key, 'content.list') }}[#imageInputPlaceholder1][order]" placeholder="{{ $params['labels']['order'] }}" class="form-control mt-3 mb-1" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-danger btn-sm remove-item float-right text-white" style="margin-top: 10px">Видалити</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="{{ constructor_field_name($key, 'content.list') }}" value="">

                    <div class="link-list-list-container" style="display: flex;gap: 5px;flex-wrap: wrap;">
                        @foreach($listData as $k => $value)
                            <div class="card item-group" style="width: 18rem;" data-item-id="{{ $k }}">
                                <div class="card-body">
                                    <div style="display: flex;gap: 10px">
                                        <div>
                                            {{ file_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][file]', $value['file'] ?? null, $errors) }}
                                        </div>
                                        <div>
                                            {{ media_preview_box(constructor_field_name($key, 'content.list') . '[' . $k . '][icon]', $value['icon'] ?? null, $errors) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                            <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][name]" placeholder="{{ $params['labels']['name'] }}" class="form-control mt-3 mb-1" value="{{ $value['name'] ?? '' }}">
                                        </div>
                                        <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                            <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][link]" placeholder="{{ $params['labels']['link'] }}" class="form-control mt-3 mb-1" value="{{ $value['link'] ?? '' }}">
                                        </div>
                                        <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                            <input type="text" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][date]" placeholder="{{ $params['labels']['date'] }}" class="form-control mt-3 mb-1" value="{{ $value['date'] ?? '' }}">
                                        </div>
                                        <div class="col-12 input-group-sm" style="margin: -5px 0;">
                                            <input type="number" name="{{ constructor_field_name($key, 'content.list') }}[{{ $k }}][order]" placeholder="{{ $params['labels']['order'] }}" class="form-control mt-3 mb-1" value="{{ $value['order'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-danger btn-sm remove-item float-right text-white" style="margin-top: 10px">Видалити</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" style="width: calc(100% - 20px);margin-left: 10px;" class="btn btn-success text-white btn-sm add-link-list-list-item_{{$lang}}">Додати елемент</button>
            </div>
        </div>
        <div class="tab-pane fade" id="pills_setting_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.anker-title',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
            <div class="row">
                <div class="col-3 mb-3 input-group-sm">
                    <label>{{ $params['labels']['type'] }}</label>
                    <select class="form-control @error(constructor_field_name_dot($key, 'content.type')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.type') }}">
                        @foreach($params['type'] as $listKey => $litItem)
                            <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.type'), $content['type'] ?? '') == $listKey)) selected @endif>{{ $litItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 mb-3 input-group-sm">
                    <label>{{ $params['labels']['file_btn_name'] }}</label>
                    <div class="form-group input-group-sm mb-12">
                        <input type="text" placeholder="{{ $params['labels']['file_btn_name'] }}" class="form-control @error(constructor_field_name_dot($key, 'content.file_btn_name')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.file_btn_name') }}" value="{{ old(constructor_field_name_dot($key, 'content.file_btn_name'), $content['file_btn_name'] ?? '') }}">
                    </div>
                </div>
                <div class="col-3 mb-3 input-group-sm">
                    <label>{{ $params['labels']['link_btn_name'] }}</label>
                    <div class="form-group input-group-sm mb-12">
                        <input type="text" placeholder="{{ $params['labels']['link_btn_name'] }}" class="form-control @error(constructor_field_name_dot($key, 'content.link_btn_name')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.link_btn_name') }}" value="{{ old(constructor_field_name_dot($key, 'content.link_btn_name'), $content['link_btn_name'] ?? '') }}">
                    </div>
                </div>
            </div>
            @include('constructor::pieces.common',['key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
        <div class="tab-pane fade" id="pills_btns_{{ $key }}_{{$lang}}">
            @include('constructor::pieces.btns',['name_component'=>'link-list','key' => $key,'params' => $params,'content' => $content, 'lang' => $lang])
        </div>
    </div>
</div>

@include('constructor::layouts.footer')
