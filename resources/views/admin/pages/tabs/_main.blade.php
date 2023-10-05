<div class="form-group row">
    <label class="col-md-3 text-right @if($lang == config('translatable.locale')) required @endif" for="page_title_{{ $lang }}">Заголовок</label>
    <div class="col-md-9">
        <input type="text" name="page_data[{{ $lang }}][title]" value="{{ old('page_data.' . $lang . '.title', $data[$lang]['title'] ?? '') }}" id="page_title_{{ $lang }}" class="form-control{{ $errors->has('page_data.' . $lang . '.title') ? ' is-invalid' : '' }}">

        @if ($errors->has('page_data.' . $lang . '.title'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.title') }}</strong>
            </span>
        @endif
    </div>
</div>
@php
    $is_setting = app(Setting::class)->get('about_page_page_id', config('translatable.locale')) == $model->id;
@endphp

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_description_{{ $lang }}">
        @if ($is_setting)
            {{ __('Sidebar') }}
        @else
            {{ __('SEO Text') }}
        @endif
    </label>
    <div class="col-md-9">
        <textarea
            name="page_data[{{ $lang }}][description]"
            id="page_description_{{ $lang }}"
            class="summernote editor"
            cols="30"
            rows="10"
        >{{ old('page_data.' . $lang . '.description', $data[$lang]['description'] ?? '') }}</textarea>
    </div>
</div>
