<div class="widget-simple-text">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    <div>
        {!! $data['text'] !!}
    </div>
</div>
