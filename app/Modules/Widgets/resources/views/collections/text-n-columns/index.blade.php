<div class="widget-text-n-columns">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    @if(isset($data['list']) && count($data['list']))
        @foreach($data['list'] as $row)
            <div>
                @foreach($row as $item)
                    <div>{!! $item['column_text'] !!}</div>
                @endforeach
            </div>
        @endforeach
    @endif
</div>
