@if (!empty($list))
    <div class="form-group">
        <label for="widget{{ studly_case($field['name']) }}">{{ trans($field['label']) }}</label>

        <div class="input-group input-group-sm">
            <select id="widget{{ studly_case($field['name']) }}" class="form-control {{ $field['class'] }} @error($field['name']) is-invalid @enderror" name="{{ $field['name'] }}">
                @foreach($list() as $key => $name)
                    <option value="{{ $key }}" @if (old($field['name'], $value) == $key) selected @endif>{{ $name }}</option>
                @endforeach
            </select>

            @error($field['name'])
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
@endif
