<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ trans($field['label']) }}</label>

    {{ file_preview_box(
        $field['name'],
        $value,
        $errors
      ) }}
</div>
