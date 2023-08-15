<?php $field_id = "form_field_" . uniqid(time()) ?>

<div id="{{$field_id}}" class="card card-default simple-text simple-text_uk card-component" style="position: relative;top:0;left:0;">
    <div class="display-layout"></div>

    <div class="confirm-delete-component-popup" style="display: none;">
        <h5 class="text-sm">{{ __('Are you sure you want to delete?') }}</h5>
        <button class="btn btn-sm btn-secondary confirm-button" type="button" data-action="cancel">{{ __('Cancel') }}</button>
        <button class="btn btn-sm btn-danger confirm-button text-white" type="button" data-action="confirm">{{ __('Remove') }}</button>
    </div>

    <div class="card card-outline card-info pt-1 pr-3 pb-1 pl-3 mb-0">
        <div class="card-title move-label d-inline">
            <i class="fas fa-braille mr-3"></i>
            {{ $field['label'] }}

            <span class="float-right">
                <div class="d-inline component-visibility-switch custom-switch custom-switch-off-danger custom-switch-on-success">
                    <input type="hidden" name="data[{{$field_id}}][visibility]" value="0">
                    <input type="checkbox" name="data[{{$field_id}}][visibility]" class="custom-control-input show-hide-checkbox" id="componentVisibility{{$field_id}}" value="1" @if ($field['visibility'] == 1) checked @endif>
                    <label class="custom-control-label" for="componentVisibility{{$field_id}}"></label>
                </div>

                <a href="#" class="link-inherit text-danger ml-2 remove-component" title="{{ __('Remove') }}">
                    <i class="fas fa-trash"></i>
                </a>

                 <a href="#collapse{{$field_id}}" class="text-info collapse-button ml-2 collapsed" data-toggle="collapse" aria-expanded="false">
                    <i class="far fa-caret-square-up"></i>
                </a>
            </span>
        </div>
    </div>

    <input type="hidden" name="data[{{$field_id}}][type]" value="{{$field['type']}}">

    <div id="collapse{{$field_id}}" class="card-body mt-1 collapse {{$field['collapse'] ?? ''}}">
        <div class="row">
            <label for="{{ $field_id }}" class="col-3 text-right">
                {{__('Title')}}
                <div class="help-label">
                    <span>{{__('only latin letters')}}</span>
                </div>
            </label>
            <div class="col-9">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                    </div>

                    <input type="text" name="data[{{$field_id}}][name]" value="{{$field['name'] ?? '' }}" id="{{ $field_id }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 5px">
            <label for="{{ $field_id }}" class="col-3 text-right">
                {{__('Header')}}
                <div class="help-label">
                    <span>{{__('Text before field')}}</span>
                </div>
            </label>
            <div class="col-9">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                    </div>

                    <input type="text" name="data[{{$field_id}}][title]" value="{{$field['title'] ?? '' }}" id="{{ $field_id }}" class="form-control">
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 5px">
            <label for="{{ $field_id }}" class="col-3 text-right">
                {{__('Choice options')}}
                <div class="help-label">
                    <span>{{__('At the skin row according to option')}}</span>
                    <span>{{__('For example')}}:</span>
                    <span>{{__('red:Red')}}</span>
                </div>
            </label>
            <div class="col-9">
                <textarea class="form-control" name="data[{{$field_id}}][options]" id="{{ $field_id }}" cols="30" rows="10">{{$field['options'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="row" style="margin-top: 5px">
            <label for="{{ $field_id }}" class="col-3 text-right">{{__('Display in message')}}</label>
            <div class="col-9">
                <div class="material-switch pull-left">
                    <input name="data[{{$field_id}}][show_in_message]" value="0" type="hidden" />
                    <input id="SwitchOptionSuccessStatus_{{ $field_id }}_show_in_message" name="data[{{$field_id}}][show_in_message]" value="1" type="checkbox" @if($is_new || (isset($field['show_in_message']) && $field['show_in_message'])) checked @endif />
                    <label for="SwitchOptionSuccessStatus_{{ $field_id }}_show_in_message" class="label-success"></label>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 5px">
            <label for="{{ $field_id }}" class="col-3 text-right">{{__('Title for the message')}}</label>
            <div class="col-9">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                    </div>

                    <input type="text" name="data[{{$field_id}}][shown_name]" value="{{$field['shown_name'] ?? '' }}" id="{{ $field_id }}" class="form-control">
                </div>
            </div>
        </div>

        @include("forms::fields._rules",['field' => $field, 'is_new' => $is_new, 'field_id' => $field_id])
    </div>
</div>
