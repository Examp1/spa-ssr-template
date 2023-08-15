@foreach($categories as $item)

    @php($category_name[] = $item['title'])

    @if($item['parent_id'] == 0)
        <optgroup label="{{ $item['title'] }}">
    @endif

    <option value="{{ $item['id'] }}" @if($item['id'] == $model->product_category_id) selected @endif>{{ trim(implode(' > ', $category_name), '>') }}</option>

    @if(!empty($item['children']))
        @php($categories = $item['children'])
        @include('admin.blog.articles.node')
    @endif

    @if($item['parent_id'] == 0)
        </optgroup>
    @endif

    @php(array_pop($category_name))

@endforeach
