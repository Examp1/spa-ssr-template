<select title="{{ $field_title }}" name="{{ $input_name }}" class="select2-with-icons">
    @foreach ($model::ICONS as $icon)
        <option value="{{ $icon }}" data-icon="{{ $icon }}"
            @if (old('icon', $model->{$field_name} ?? null) == $icon) selected @endif>{{ $icon }}</option>
    @endforeach
</select>

@push('styles')
    <link rel="stylesheet" href="https://i.icomoon.io/public/0b30d968be/Dream-Travel/style.css"> {{-- from \frontend\public\index.html --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px !important;
        }

        .select2-container--default {
            width: 200px !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/{{ app()->getLocale() }}.js"></script>
    <script>
        $(document).ready(() => {

            function iconFromValue(val) {
                if (val.element) {
                    val = `<i style="font-size: 1.3em" class="${val.element.value}"></i> ${val.element.value}`;
                }
                return val;
            }

            $(".select2-with-icons").select2({
                minimumResultsForSearch: Infinity,
                templateResult: iconFromValue,
                templateSelection: iconFromValue,
                escapeMarkup: function(m) {
                    return m;
                }

            });
        });
    </script>
@endpush
