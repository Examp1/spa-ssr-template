<div class="widget-see-also">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    @if(isset($data['list']) && count($data['list']))
        <ul class="see-also-list">
            @foreach($data['list'] as $row)
                <li>
                    <a href="{{get_interlink($row)['link'] ?? ''}}">{{$row['title']}}</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
