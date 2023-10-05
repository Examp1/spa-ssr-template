@foreach ($categories as $item)
    @php
        $category_name[] = $item['name'];
        $is_selected = false;
        
        if (is_array($selected) && in_array($item['id'], $selected)) {
            $is_selected = true;
        } elseif ($selected == $item['id']) {
            $is_selected = true;
        }
        
    @endphp

    @if ($item['parent_id'] == 0)
        <optgroup label="{{ $item['name'] }}">
    @endif

    <option value="{{ $item['id'] }}" @if ($is_selected) selected @endif>
        {{ trim(implode(' > ', $category_name), '>') }}
    </option>

    @if (!empty($item['children']))
        @php($categories = $item['children'])
        @include('ecom::admin.categories.options-html')
    @endif

    @if ($item['parent_id'] == 0)
        </optgroup>
    @endif

    @php(array_pop($category_name))
@endforeach
