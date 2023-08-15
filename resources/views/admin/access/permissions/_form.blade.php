<div class="form-group row">
    <label class="col-md-3 text-right required" for="page_name">{{ __('Name') }}</label>
    <div class="col-md-9">
        <input type="text" name="name" value="{{ old('name', $model->name ?? '') }}" id="page_name"
            class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">

        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right required" for="page_group_id">{{ __('Group') }}</label>
    <div class="col-md-9">
        <select name="group_id" class="form-control{{ $errors->has('group_id') ? ' is-invalid' : '' }}"
            id="page_group_id">
            <option value="">---</option>
            @foreach (\App\Models\PermissionGroups::query()->get() as $item)
                <option value="{{ $item->id }}" @if (old('group_id', $model->group_id ?? null) == $item->id) selected @endif>
                    {{ $item->name }}</option>
            @endforeach
        </select>

        @if ($errors->has('group_id'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('group_id') }}</strong>
            </span>
        @endif
    </div>
</div>
