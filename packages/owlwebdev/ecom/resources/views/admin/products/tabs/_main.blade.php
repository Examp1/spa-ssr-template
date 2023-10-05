<div class="form-group row">
    <label class="col-md-3 text-right @if ($lang == config('translatable.locale')) required @endif"
        for="page_name_{{ $lang }}">{{ __('Title') }}</label>
    <div class="col-md-9">
        <input type="text" @if ($lang == config('translatable.locale')) required @endif
            name="page_data[{{ $lang }}][name]"
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

{{-- <div class="form-group row">
    <label class="col-md-3 text-right" for="page_excerpt_{{ $lang }}">{{ __('Excerpt') }}</label>
    <div class="col-md-9">
        <textarea
            name="page_data[{{ $lang }}][excerpt]"
            id="page_excerpt_{{ $lang }}"
            class="summernote editor {{ $errors->has('page_data.' . $lang . '.excerpt') ? ' is-invalid' : '' }}"
            cols="30"
            rows="10"
        >{{ old('page_data.' . $lang . '.excerpt', $data[$lang]['excerpt'] ?? '') }}</textarea>

        @if ($errors->has('page_data.' . $lang . '.excerpt'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.excerpt') }}</strong>
            </span>
        @endif
    </div>
</div> --}}

@include('ecom::admin.pieces.main_screen', [
    'data' => $data ?? [],
    'lang' => $lang,
])

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_info_{{ $lang }}">{{ __('Product information') }}</label>
    <div class="col-md-9">
        <textarea name="page_data[{{ $lang }}][info]" id="page_info_{{ $lang }}"
            class="summernote editor {{ $errors->has('page_data.' . $lang . '.info') ? ' is-invalid' : '' }}" cols="30"
            rows="10">{{ old('page_data.' . $lang . '.info', $data[$lang]['info'] ?? '') }}</textarea>

        @if ($errors->has('page_data.' . $lang . '.info'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('page_data.' . $lang . '.info') }}</strong>
            </span>
        @endif
    </div>
</div>

@push('styles')
@endpush

@push('scripts')
    <script>
        $(document).ready(() => {

        });
    </script>
@endpush
