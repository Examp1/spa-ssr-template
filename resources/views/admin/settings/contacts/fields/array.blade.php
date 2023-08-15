<div class="form-group row">
    <label class="col-md-3 text-right">{{$fieldTitle}}</label>
    <div class="col-md-9">
        <div class="input-group mb-1">
            <div style="display: none;">
                <div data-item-id="#dynamicListPlaceholder" class="item-{{$fieldName}}-template-none-{{$lang}}-{{$countContactBlock}} item-group input-group mb-1">
                    @foreach($fields as $field)
                        @switch($field['fieldType'])
                            @case('text')
                            <input type="text"
                                   placeholder="{{$field['fieldTitle']}}"
                                   name="setting_data[{{ $lang }}][contacts][{{$countContactBlock}}][{{$fieldName}}][#dynamicListPlaceholder][{{$field['fieldName']}}]"
                                   class="form-control mr-1"
                                   disabled=""
                            >
                            @break
                            @case('number')
                            <input type="number"
                                   placeholder="{{$field['fieldTitle']}}"
                                   name="setting_data[{{ $lang }}][contacts][{{$countContactBlock}}][{{$fieldName}}][#dynamicListPlaceholder][{{$field['fieldName']}}]"
                                   class="form-control mr-1"
                                   disabled=""
                            >
                            @break
                            @case('image')
                            {{ media_preview_box("setting_data[".$lang."][contacts][".$countContactBlock."][".$fieldName."][#dynamicListPlaceholder][".$field['fieldName']."]") }}
                            @break
                        @endswitch
                    @endforeach
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-item text-white">{{ __('Remove') }}</button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="setting_data[{{ $lang }}][contacts][{{$countContactBlock}}][{{$fieldName}}]" value="">

            @php($dataArray = $contact[$fieldName] ?? [])
            <div class="items-{{$fieldName}}-container-{{$lang}}-{{$countContactBlock}} w-100">
                @foreach($dataArray as $k => $value)
                    <div data-item-id="{{$k}}" class="item-template item-group input-group mb-1">
                        @foreach($fields as $field)
                            @switch($field['fieldType'])
                                @case('text')
                                <input type="text"
                                       placeholder="{{$field['fieldTitle']}}"
                                       name="setting_data[{{ $lang }}][contacts][{{$countContactBlock}}][{{$fieldName}}][{{$k}}][{{$field['fieldName']}}]"
                                       class="form-control mr-1"
                                       value="{{$value[$field['fieldName']]}}"
                                >
                                @break
                                @case('number')
                                <input type="number"
                                       placeholder="{{$field['fieldTitle']}}"
                                       name="setting_data[{{ $lang }}][contacts][{{$countContactBlock}}][{{$fieldName}}][{{$k}}][{{$field['fieldName']}}]"
                                       class="form-control mr-1"
                                       value="{{$value[$field['fieldName']]}}"
                                >
                                @break
                                @case('image')
                                {{ media_preview_box("setting_data[".$lang."][contacts][".$countContactBlock."][".$fieldName."][".$k."][".$field['fieldName']."]",$value[$field['fieldName']] ?? '') }}
                                @break
                            @endswitch
                        @endforeach
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger remove-item text-white">{{ __('Remove') }}</button>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <button type="button"
                class="btn btn-info btn-sm add-item-array-field"
                data-field_name="{{$fieldName}}"
                data-lang="{{$lang}}"
                data-count_contact_block="{{$countContactBlock}}"
        >{{ __('Add') }}</button>
    </div>
</div>
