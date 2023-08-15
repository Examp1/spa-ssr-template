<?php
$formsList = \App\Modules\Forms\Models\Form::query()
    ->where('lang',$lang)
    ->pluck('name', 'id')
    ->toArray();
?>
<div class="interlink-container">
    <div class="input-group-prepend">
        <span class="input-group-text">
            <i class="fa fa-link"></i>
        </span>
    </div>
    <select class="form-control interlink-select-type-{{$lang}}" name="{{$name_type}}">
        <option value="none" @if(isset($sel_type) && $sel_type == 'none') selected @endif>---</option>
        @foreach(config('menu.entities') as $key => $mType)
            <option value="{{$key}}" @if(isset($sel_type) && $sel_type == $key) selected @endif>{{$mType::SHOWN_NAME}}</option>
        @endforeach
        <option value="arbitrary" @if(isset($sel_type) && $sel_type == 'arbitrary') selected @endif>Довільне посилання</option>
        <option value="form" @if(isset($sel_type) && $sel_type == 'form') selected @endif>Форма</option>
    </select>

    @foreach(config('menu.entities') as $key => $mType)
        <div class="select-type select-type-{{$key}}" @if(((!isset($sel_type) && $key === 'Pages') || (isset($sel_type) && $sel_type == $key)) && ((isset($sel_type) && $sel_type != 'none'))) style="display: initial;" @else style="display: none;" @endif>
            <select class="form-control @isset($sel_type) select2-internal-init @else select2-internal @endif" style="width: 300px" name="{{$name_val}}[{{$key}}]">
                {!! $mType::getOptionsHTML($sel_val[$sel_type ?? null] ?? null) !!}
            </select>
        </div>
    @endforeach

    <input type="text" class="form-control select-type select-type-arbitrary" @if(isset($sel_type) && $sel_type == 'arbitrary') value="{{$sel_val[$sel_type]}}" @endif  @if(isset($sel_type) && $sel_type == 'arbitrary') style="display: initial;" @else style="display: none;" @endif name="{{$name_val}}[arbitrary]">
    <select name="{{$name_val}}[form]"
            class="form-control select-type select-type-form"
            @if(isset($sel_type) && $sel_type == 'form') style="display: initial;" @else style="display: none;" @endif
    >
        @foreach ($formsList as $formListKey => $formList)
            <option value="{{ $formListKey }}" @if(isset($sel_type) && $sel_type == 'form' && $sel_val[$sel_type] == $formListKey) selected @endif>{{ $formList }}</option>
        @endforeach
    </select>
</div>
