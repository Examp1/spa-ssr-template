<div class="twocolDiv">
@if($content['image_position'] == 'left')
    <div class="imgdiv">
        <img src="{{get_image_uri($content['image'], 'original')}}" alt="">
    </div>
    <div class="txtdiv">
        @if(! empty($content['title']))
            <h3>{{$content['title']}}</h3>
        @endif
        {!! $content['description'] !!}
    </div>
@elseif($content['image_position'] == 'right')
    <div class="txtdiv">
        @if(! empty($content['title']))
            <h3>{{$content['title']}}</h3>
        @endif
        {!! $content['description'] !!}
    </div>
    <div class="imgdiv">
        <img src="{{get_image_uri($content['image'], 'original')}}" alt="">
    </div>
@endif
</div>
