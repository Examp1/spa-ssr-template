<div class="form-group row">
    <label class="col-md-3 text-right" for="page_name_{{ $lang }}">{{ __('Title') }}</label>
    <div class="col-md-9">
        <input type="text" name="page_data[{{ $lang }}][name]" value="{{ old('page_data.' . $lang . '.name', $data[$lang]['name'] ?? '') }}" id="page_name_{{ $lang }}" class="form-control{{ $errors->has('page_data.' . $lang . '.name') ? ' is-invalid' : '' }}">

        @if ($errors->has('page_data.' . $lang . '.name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_description_{{ $lang }}">{{ __('Text') }}</label>
    <div class="col-md-9">
        <input type="description" name="page_data[{{ $lang }}][description]" value="{{ old('page_data.' . $lang . '.description', $data[$lang]['description'] ?? '') }}" id="page_description_{{ $lang }}" class="form-control{{ $errors->has('page_data.' . $lang . '.description') ? ' is-invalid' : '' }}">

        @if ($errors->has('page_data.' . $lang . '.description'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.description') }}</strong>
            </span>
        @endif
    </div>
</div>
