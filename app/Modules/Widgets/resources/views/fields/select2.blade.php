@if (!empty($list))
    <div class="form-group">
        <label for="widget{{ studly_case($field['name']) }}">{{ trans($field['label']) }}</label>

        <div class="input-group input-group-sm">
            <select id="widget{{ studly_case($field['name']) }}"
                class="select2-field @error($field['name']) is-invalid @enderror"
                @if (isset($field['width']) && $field['width']) style="width: {{ $field['width'] }}" @endif
                @if (isset($field['multiple']) && $field['multiple']) multiple
                        name="{{ $field['name'] }}[]"
                    @else
                        name="{{ $field['name'] }}" @endif>
                @if (isset($field['multiple']) && $field['multiple'])
                    @foreach ($list() as $key => $name)
                        <option value="{{ $key }}" @if (in_array($key, old($field['name'], $value))) selected @endif>
                            {{ $name }}</option>
                    @endforeach
                @else
                    @foreach ($list() as $key => $name)
                        <option value="{{ $key }}" @if (old($field['name'], $value) == $key) selected @endif>
                            {{ $name }}</option>
                    @endforeach
                @endif
            </select>

            @error($field['name'])
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
@endif

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    @if (isset($field['multiple']) && $field['multiple'])
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

            .select2-container--default .select2-selection--multiple {
                height: inherit;
            }
        </style>
    @endif
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            @if (isset($field['multiple']) && $field['multiple'])
                $('.select2-field').select2();
            @else
                $(".select2-field").select2();
            @endif
        });
    </script>
@endpush
