@foreach (\App\Models\BlogArticles::query()->active()->get() as $item)
    <option value="{{ $item->id }}"
            title="{{ $item->name }}"
            @if(isset($selected) && $selected == $item->id) selected @endif
    >{{ mb_strimwidth($item->name, 0, 30, '...') }}</option>
@endforeach
