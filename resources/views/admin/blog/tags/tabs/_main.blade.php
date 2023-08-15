<div class="form-group row">
    <label class="col-md-3 text-right" for="page_name_{{ $lang }}">{{ __('Title') }}</label>
    <div class="col-md-9">
        <input type="text" name="page_data[{{ $lang }}][name]"
            value="{{ old('page_data.' . $lang . '.name', $data[$lang]['name'] ?? '') }}"
            id="page_name_{{ $lang }}"
            class="form-control{{ $errors->has('page_data.' . $lang . '.name') ? ' is-invalid' : '' }}">

        @if ($errors->has('page_data.' . $lang . '.name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.name') }}</strong>
            </span>
        @endif
    </div>
</div>

{{-- <div class="form-group row"> --}}
{{--    <label class="col-md-3 text-right" for="page_description_{{ $lang }}">{{ __('Description') }}</label> --}}
{{--    <div class="col-md-9"> --}}
{{--        <textarea --}}
{{--            name="page_data[{{ $lang }}][description]" --}}
{{--            id="page_description_{{ $lang }}" --}}
{{--            class="summernote editor {{ $errors->has('page_data.' . $lang . '.description') ? ' is-invalid' : '' }}" --}}
{{--            cols="30" --}}
{{--            rows="10" --}}
{{--        >{{ old('page_data.' . $lang . '.description', $data[$lang]['description'] ?? '') }}</textarea> --}}

{{--        @if ($errors->has('page_data.' . $lang . '.description')) --}}
{{--            <span class="invalid-feedback" role="alert"> --}}
{{--                <strong>{{ $errors->first('page_data.' . $lang . '.description') }}</strong> --}}
{{--            </span> --}}
{{--        @endif --}}
{{--    </div> --}}
{{-- </div> --}}

{{-- <div class="form-group row"> --}}
{{--    <label class="col-md-3 text-right">{{ __('Image') }}</label> --}}
{{--    <div class="col-md-9"> --}}
{{--        {{ media_preview_box( --}}
{{--        'page_data['.$lang.'][image]', --}}
{{--        old('page_data.' . $lang . '.image', $data[$lang]['image'] ?? ''), --}}
{{--        null, --}}
{{--        'page_data['.$lang.'][alt]', --}}
{{--        old('page_data.' . $lang . '.alt', $data[$lang]['alt'] ?? '') --}}
{{--        ) }} --}}
{{--    </div> --}}
{{-- </div> --}}
