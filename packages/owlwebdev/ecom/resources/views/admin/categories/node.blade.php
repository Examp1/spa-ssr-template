@foreach ($categories as $item)
    @php($category_name[] = $item['name'])

    <option value="{{ $item['id'] }}" @if (isset($model->id) && $model->id == $item['id']) disabled @endif
        @if (old('parent_id', $model->parent_id ?? '') == $item['id']) selected @endif>{{ trim(implode(' > ', $category_name), '>') }}
    </option>

    @if (!empty($item['children']))
        @php($categories = $item['children'])
        @include('ecom::admin.categories.node')
    @endif

    @php(array_pop($category_name))
@endforeach
