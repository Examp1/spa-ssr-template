<?php
$formsList = \App\Modules\Forms\Models\Form::query()->pluck('name','id')->toArray();
?>

<div class="input-group">
    <div style="display: none;">
        <div data-item-id="#btnInputPlaceholder" class="{{$name_component}}-btn-list-template item-group m-1 border border-grey-light p-1 d-flex align-items-center qwert">
            <div class="col-5">
                <input type="text" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][text]" placeholder="Текст" class="form-control mt-3 mb-1 btn-preview-text" disabled>

                <select name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][type_link]" class="form-control type_link">
                    <option value="link">Довільне посилання</option>
                    <option value="form">Форма</option>
                </select>

                <input type="text" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][link]" placeholder="Посилання" class="form-control type_link_link mt-3 mb-1 btn-preview-link" disabled>

                <select name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][form_id]" class="form-control type_link_form" style="display: none">
                    @foreach($formsList as $formListKey => $formList)
                        <option value="{{$formListKey}}">{{$formList}}</option>
                    @endforeach
                </select>

                <select class="form-control form-control mt-3 mb-1 btn-preview-type" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][type]">
                    @foreach($params['btn_type'] as $listKey => $listItem)
                        <option value="{{ $listKey }}">{{ $listItem }}</option>
                    @endforeach
                </select>
                <select class="form-control btn-icon-select2 btn-preview-icon" style="width: 150px" name="{{ constructor_field_name($key, 'content.btns') }}[#btnInputPlaceholder][icon]">
                    @foreach($params['btn_icon'] as $listKey => $listItem)
                        <option value="{{ $listKey }}" data-icon="{{$listKey}}">{{ $listItem }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-5">
                <div style="width: 100%" class="btn-preview-block">
                    <a href="javascript:void(0)" target="_blank"><span></span><i></i></a>
                </div>
            </div>

            <div class="col-2">
                <button type="button" class="btn btn-danger remove-item float-right text-white">Видалити</button>
            </div>
        </div>
    </div>

    <input type="hidden" name="{{ constructor_field_name($key, 'content.btns') }}" value="">

    <div class="{{$name_component}}-btn-list-container w-100">
        @foreach((array) old(constructor_field_name($key, 'content.btns'), $content['btns'] ?? []) as $k => $value)
            <div data-item-id="{{ $k }}" class="item-template item-group m-1 border border-grey-light p-1 d-flex align-items-center qwert">
                <div class="col-5">
                    <input type="text" name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][text]" placeholder="Текст" class="form-control mt-3 mb-1 btn-preview-text" value="{{ $value['text'] ?? '' }}">

                    <?php $type_link = $value['type_link'] ?? ''; ?>
                    <select name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][type_link]" class="form-control type_link">
                        <option value="link" @if($type_link == 'link') selected @endif>Довільне посилання</option>
                        <option value="form" @if($type_link == 'form') selected @endif>Форма</option>
                    </select>

                    <input type="text"
                           name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][link]"
                           placeholder="Посилання"
                           class="form-control type_link_link mt-3 mb-1 btn-preview-link"
                           value="{{ $value['link'] ?? '' }}"
                           style="@if($type_link != 'link') display: none @endif"
                    >

                    <?php $form_id = $value['form_id'] ?? ''; ?>
                    <select name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][form_id]"
                            class="form-control type_link_form"
                            style="@if($type_link !== 'form') display: none @endif"
                    >
                        @foreach($formsList as $formListKey => $formList)
                            <option value="{{$formListKey}}" @if($form_id == $formListKey) selected @endif>{{$formList}}</option>
                        @endforeach
                    </select>


                    <?php $type = $value['type'] ?? ''; ?>
                    <select class="form-control mt-3 mb-1 btn-preview-type" name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][type]">
                        @foreach($params['btn_type'] as $listKey => $listItem)
                            <option value="{{ $listKey }}" @if($type == $listKey) selected @endif>{{ $listItem }}</option>
                        @endforeach
                    </select>
                    <?php $icon = $value['icon'] ?? ''; ?>
                    <select class="form-control btn-icon-select2-ready btn-preview-icon" style="width: 150px" name="{{ constructor_field_name($key, 'content.btns') }}[{{ $k }}][icon]">
                        @foreach($params['btn_icon'] as $listKey => $listItem)
                            <option value="{{ $listKey }}" @if($icon == $listKey) selected @endif data-icon="{{$listKey}}">{{ $listItem }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-5">
                    <div style="width: 100%" class="btn-preview-block">
                        <a href="{{$value['link'] ?? 'javascript:void(0)'}}" target="_blank" class="style-btn {{$type}} @if($icon !== "non") hasIcon @endif">
                            <span>{{$value['text'] ?? ''}}</span>
                            <i class="@if($icon !== "non") linkIcon {{$icon}} @endif" @if($type === "text") style="display: none" @endif></i>
                        </a>
                    </div>
                </div>

                <div class="col-2">
                    <button type="button" class="btn btn-danger remove-item text-white float-right">Видалити</button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<button type="button" class="btn btn-info btn-sm {{$name_component}}-add-btn-list-item_{{$lang}} d-block mt-2">Додати кнопку</button>
