@php($isNew = count($content) ? false : true)

<div class="row">
    <div class="col-3 input-group-sm mb-3">
        <label>{{ $params['labels']['top_separator'] }}</label>
        <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.top_separator')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.top_separator') }}">
            @foreach($params['separator'] as $listKey => $litItem)
                <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.top_separator'), $content['top_separator'] ?? '') == $listKey) || ($isNew && $listKey === 'M')) selected @endif>{{ $litItem }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-3 input-group-sm mb-3">
        <label>{{ $params['labels']['bottom_separator'] }}</label>
        <select class="form-control select-background @error(constructor_field_name_dot($key, 'content.bottom_separator')) is-invalid @enderror" name="{{ constructor_field_name($key, 'content.bottom_separator') }}">
            @foreach($params['separator'] as $listKey => $litItem)
                <option value="{{ $listKey }}" @if ((old(constructor_field_name_dot($key, 'content.bottom_separator'), $content['bottom_separator'] ?? '') == $listKey) || ($isNew && $listKey === 'M')) selected @endif>{{ $litItem }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-3 input-group-sm mb-3">
        <label>Колір фону</label>
        @php($background_type_val = old(constructor_field_name_dot($key, 'content.background_type'), $content['background_type'] ?? 'transparent'))
        <select name="{{ constructor_field_name($key, 'content.background_type') }}" class="form-control">
            <option value="transparent" @if($background_type_val == "transparent") selected @endif>Без фону</option>
            <option value="white" @if($background_type_val == "white") selected @endif>Білий</option>
            <option value="simple" @if($background_type_val == "simple") selected @endif>Довільний колір</option>
        </select>
    </div>
    <div class="col-3 input-group-sm mb-3">
        <label>Довільний колір</label>
        <input type="color"
               class="form-control"
               name="{{ constructor_field_name($key, 'content.background') }}"
               value="{{ old(constructor_field_name_dot($key, 'content.background'), $content['background'] ?? '#ffffff') }}"
        >
    </div>
</div>
