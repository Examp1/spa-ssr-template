<div class="widget-video-and-text">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    <div>{{get_file_uri($data['video'])}}</div>
    <div>{!! $data['text'] !!}</div>
</div>
