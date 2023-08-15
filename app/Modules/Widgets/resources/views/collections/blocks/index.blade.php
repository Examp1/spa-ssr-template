<div class="widget-blocks">
    @isset($data['title'])
        <p class="title">{{$data['title']}} {{$data['width']}}</p>
    @endisset
    @if(isset($data['list']) && count($data['list']))
        <div class="blocks-list">
            @foreach($data['list'] as $row)
                <div class="blocks-item">
                    <div>
                        <a href="{{$row['link']}}" target="_blank">
                            <img src="{{get_image_uri($row['image'])}}" alt="">
                        </a>
                    </div>
                    <div>{!! $row['text'] !!}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
