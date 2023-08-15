<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ trans($field['label']) }}</label>

    <div class="input-group">
        <textarea name="{{ $field['name'] }}" rows="6" id="widget{{ studly_case($field['name']) }}" class="form-control {{ $field['class'] }} @error($field['name']) is-invalid @enderror">{{ old($field['name'], $value) }}</textarea>

        @error($field['name'])
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
