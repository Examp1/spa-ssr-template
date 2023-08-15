<div class="form-group">
    <label for="widget{{ studly_case($field['name']) }}">{{ trans($field['label']) }}</label>

    <div class="input-group input-group-sm">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="far fa-envelope"></i></span>
        </div>
        <input type="email" name="{{ $field['name'] }}" value="{{ old($field['name'], $value) }}" id="widget{{ studly_case($field['name']) }}" class="form-control {{ $field['class'] }} @error($field['name']) is-invalid @enderror">

        @error($field['name'])
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
