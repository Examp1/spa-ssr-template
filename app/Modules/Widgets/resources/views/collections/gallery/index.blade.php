<div class="widget-gallery">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    @if(isset($data['list']) && count($data['list']))
        <div class="gallery-list">
            @foreach($data['list'] as $row)
                <div class="gallery-item">
                    <img src="{{get_image_uri($row['image'])}}" alt="">
                    <div>{{$row['title']}}</div>
                    <div>{!! $row['text'] !!}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
