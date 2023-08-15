<div class="form-group row">
    <label class="col-md-3 text-right @if ($lang == config('translatable.locale')) required @endif"
        for="page_name_{{ $lang }}">Заголовок</label>
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

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_text_{{ $lang }}">Контент</label>
    <div class="col-md-9">
        <textarea name="page_data[{{ $lang }}][text]" id="page_text_{{ $lang }}"
            class="summernote editor {{ $errors->has('page_data.' . $lang . '.text') ? ' is-invalid' : '' }}" cols="30"
            rows="10">{{ old('page_data.' . $lang . '.text', $data[$lang]['text'] ?? '') }}</textarea>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #5897fb !important;
            border: 1px solid #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color: #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            color: #3e5569;
            background-color: #fff;
            border-color: rgba(0, 0, 0, 0.25);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }

        .select2-container--classic .select2-selection--single,
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--single .select2-selection__arrow,
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            height: inherit;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(() => {
            // $('.select2').select2();
        });
    </script>
@endpush
