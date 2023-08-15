<div class="form-group row">
    <label class="col-md-3 text-right required" for="page_name">{{ __('Title') }}</label>
    <div class="col-md-9">
        <input type="text" name="name" value="{{ old('name', $model->name ?? '') }}" id="page_name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>
