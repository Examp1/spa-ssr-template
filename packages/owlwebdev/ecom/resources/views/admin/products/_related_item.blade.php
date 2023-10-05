<div class="related-container">
    @foreach($selected as $sel_prod)
        <div class="form-group row related-element">
            <div class="col-md-1 btns-group">
                <span class="btn btn-danger text-white remove-related-item">
                    <i class="mdi mdi-delete"></i>
                </span>
            </div>
            <div class="col-md-11">
                <select class="select2" name="{{ $field }}[]">
                    @foreach ($products as $prod)
                        <option value="{{ $prod->id }}" {{ $prod->id == $sel_prod ? 'selected="selected"': '' }}>{{ $prod->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endforeach
</div>
<template id="{{ $field }}_template">
    <div class="form-group row related-element">
        <div class="col-md-1 btns-group">
            <span class="btn btn-danger text-white remove-related-item">
                <i class="mdi mdi-delete"></i>
            </span>
        </div>
        <div class="col-md-11">
            <select class="select2" name="{{ $field }}[]">
                @foreach ($products as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</template>
