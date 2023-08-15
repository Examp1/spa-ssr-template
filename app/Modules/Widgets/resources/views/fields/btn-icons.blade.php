<?php
$iconVal = old($field['name'], $value);
?>

<div class="form-group">
    <label for="widget">{{ trans($field['label']) }}</label>
    <div class="input-group">
        <select name="{{ $field['name'] }}" class="form-control w-btn-icon-select2-ready btn-preview-icon"
            style="margin-top: 5px; width: 150px;">
            @foreach (config('buttons.icon') as $key2 => $item)
                <option value="{{ $key2 }}" @if ($iconVal == $key2) selected @endif
                    data-icon="{{ $key2 }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        function formatStateIcon(state) {
            if (!state.id) {
                return state.text;
            }
            if (state.element.dataset.icon && state.element.dataset.icon !== "non") {
                var $state = $(
                    '<span class="' + state.element.dataset.icon + '"></span>'
                );
            } else {
                var $state = $(
                    '<span> ' + state.text + '</span>'
                );
            }
            return $state;
        };
        $('.btn-preview-icon').select2({
            templateResult: formatStateIcon,
            templateSelection: formatStateIcon,
        })
    </script>
@endpush
