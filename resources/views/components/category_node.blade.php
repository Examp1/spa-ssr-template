@foreach($categories as $item)

    @php($category_name[] = $item['title'])

    @if($item['parent_id'] == 0)
        <optgroup label="{{ $item['title'] }}">
    @endif

    <option value="{{ $item['id'] }}" @if($item['id'] == $sel) selected @endif>{{ trim(implode(' > ', $category_name), '>') }}</option>

    @if(!empty($item['children']))
        @php($categories = $item['children'])
        @include('components.category_node',['sel' => $sel])
    @endif

    @if($item['parent_id'] == 0)
        </optgroup>
    @endif

    @php(array_pop($category_name))

@endforeach
