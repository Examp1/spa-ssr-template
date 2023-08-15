<?php
$interlinkSel = old($field['name'], $value);
if (isset($interlinkSel) && is_array($interlinkSel) && isset($interlinkSel['interlink_type']) && isset($interlinkSel['interlink_val'])) {
    $interlinkSelType = $interlinkSel['interlink_type'];
    $interlinkSelVal = $interlinkSel['interlink_val'];
}
?>

@include('admin.pieces.fields.interlink', [
    'lang' => studly_case($field['name']),
    'label' => trans($field['label']),
    'name_type' => $field['name'] . '[interlink_type]',
    'name_val' => $field['name'] . '[interlink_val]',
    'sel_type' => $interlinkSelType ?? null,
    'sel_val' => $interlinkSelVal ?? null,
])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('.items-container').find('.select2-field').each(function() {
            $(this).select2({});
        });

        /* INTERLINK component */
        $(document).on('change', '.interlink-select-type-{{ studly_case($field['name']) }}', function() {
            let type = $(this).val();
            console.log(type)
            $(this).siblings('.select-type').hide();
            $(this).siblings('.select-type-' + type).show();
        });
    </script>
@endpush
