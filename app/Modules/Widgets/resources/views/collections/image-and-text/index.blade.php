<div class="widget-image-and-text">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    @if($data['position'] === 'left')
        <div>
            <img src="{{get_image_uri($data['image'])}}" alt="" style="float: left">
            {!! $data['text'] !!}
        </div>
    @else
        <div>
            <img src="{{get_image_uri($data['image'])}}" alt="" style="float: right">
            {!! $data['text'] !!}
        </div>
    @endif
</div>
