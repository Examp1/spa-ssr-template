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

@include('ecom::admin.pieces.main_screen', [
    'data' => $data??[],
    'lang' => $lang,
    ])

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_excerpt_{{ $lang }}">{{ __('SEO Text') }}</label>
    <div class="col-md-9">
        <textarea name="page_data[{{ $lang }}][excerpt]" id="page_excerpt_{{ $lang }}"
            class="summernote editor {{ $errors->has('page_data.' . $lang . '.excerpt') ? ' is-invalid' : '' }}" cols="30"
            rows="10">{{ old('page_data.' . $lang . '.excerpt', $data[$lang]['excerpt'] ?? '') }}</textarea>

        @if ($errors->has('page_data.' . $lang . '.excerpt'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.excerpt') }}</strong>
            </span>
        @endif
    </div>
</div>



<?php
// $options = [];
// if(isset($data[$lang]['options'])){
//     $options = json_decode($data[$lang]['options'],true);
// }
?>

{{-- <div class="form-group row">
    <label class="col-md-3 text-right" for="page_options_{{ $lang }}">Опции</label>
    <div class="col-md-9">
        <select name="page_data[{{ $lang }}][options][]" multiple id="page_options_{{ $lang }}" class="select2-field-tagable" style="width: 100%">
            @if (is_array($options) && count($options))
                @foreach ($options as $option)
                        <option value="{{$option}}" selected>{{$option}}</option>
                @endforeach
            @endif
        </select>

        @if ($errors->has('page_data.' . $lang . '.options'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.options') }}</strong>
            </span>
        @endif
    </div>
</div> --}}
