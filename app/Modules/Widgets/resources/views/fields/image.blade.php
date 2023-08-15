<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ trans($field['label']) }}</label>

    {{ media_preview_box(
        $field['name'],
        $value,
        $errors,
        $field['alt_name'],
        $alt_value ?? '',
      ) }}
</div>
