<div class="section-text-n-columns">
@if (!empty($content['title']))
    <div class="title">{{ $content['title'] }}</div>
@endif
@if(isset($content['rows']) && count($content['rows']))
    @foreach($content['rows'] as $row)
        <div>
            @foreach($row as $item)
                <div>{!! $item['column_text'] !!}</div>
            @endforeach
        </div>
    @endforeach
@endif
</div>

