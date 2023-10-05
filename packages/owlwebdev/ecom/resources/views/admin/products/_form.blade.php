<div class="form-group row">
    <label class="col-md-3 text-right" for="page_categories">{{ __('Category') }}</label>
    <div class="col-md-9" id="categories-list">
        <select class="select2-field" multiple="multiple" name="categories[]" id="categories" style="width: 100%">
            {!! \Owlwebdev\Ecom\Models\Category::getOptionsHTML($model->categories->pluck('id')->toArray()) !!}
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right">{{ __('Image') }}</label>
    <div class="col-md-9">
        {{ media_preview_box('image', $model->image) }}
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_order">{{ __('Sort order') }}</label>
    <div class="col-md-9">
        <input type="number" name="order" value="{{ old('order', $model->order ?? 25) }}" id="page_order"
            class="form-control{{ $errors->has('order') ? ' is-invalid' : '' }}">

        @if ($errors->has('order'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('order') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_slug">Slug</label>
    <div class="col-md-9">
        <input type="text" name="slug" value="{{ old('slug', $model->slug ?? '') }}" id="page_slug"
            class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}">

        @if ($errors->has('slug'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('slug') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label class="col-md-3 text-right" for="page_status">{{ __('Status') }}</label>
    <div class="col-md-9">
        <div class="material-switch pull-left">
            <input id="someSwitchOptionSuccess" name="status" value="1" type="checkbox"
                {{ old('status', $model->status) ? ' checked' : '' }} />
            <label for="someSwitchOptionSuccess" class="label-success"></label>
        </div>

        @if ($errors->has('status'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('status') }}</strong>
            </span>
        @endif
    </div>
</div>
