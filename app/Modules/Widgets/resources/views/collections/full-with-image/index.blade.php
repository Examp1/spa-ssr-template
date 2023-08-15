<div class="widget-full-width-image">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    <img src="{{get_image_uri($data['image'])}}" alt="">
</div>
