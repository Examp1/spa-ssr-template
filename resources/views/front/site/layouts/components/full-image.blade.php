<div class="section-full-image">
    @if(! empty($content['title']))
        <div class="title">{{$content['title']}}</div>
    @endif
    <img src="{{get_image_uri($content['image'], 'original')}}" alt="">
</div>
