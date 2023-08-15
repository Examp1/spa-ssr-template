<div class="section-gallery">
    @if(! empty($content['title']))
        <div class="title">{!! $content['title'] !!}</div>
    @endif
    @if(! empty($content['list']) && count($content['list']))
    <div class="gallery-lits">
        @foreach($content['list'] as $item)
            <div class="gallery-item">
                <img src="{{get_image_uri($item['image'],'original')}}" alt="">
            </div>
        @endforeach
    </div>
    @endif
</div>
