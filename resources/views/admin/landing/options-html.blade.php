@foreach (\App\Models\Landing::query()->active()->get() as $item)
    <option value="{{ $item->id }}"
            title="{{ $item->title }}"
            @if(isset($selected) && $selected == $item->id) selected @endif
    >{{ mb_strimwidth($item->title, 0, 30, '...') }}</option>
@endforeach
