@if(isset($content['list']) && count($content['list']))
    <ul>
        @foreach($content['list'] as $item)
            <li>
                <a href="{{get_interlink($item)['link'] ?? ''}}">{{$item['title']}}</a>
            </li>
        @endforeach
    </ul>
@endif
