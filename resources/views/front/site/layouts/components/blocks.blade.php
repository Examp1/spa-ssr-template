<div class="section-blocks">
    @if (!empty($content['title']))
        <div class="title">{{ $content['title'] }}</div>
    @endif
</div>

@if(isset($content['list']) && count($content['list']))
    <div class="blocks-list">
        @foreach($content['list'] as $item)
            <div class="item">
                <div class="blocks-item-text">{!! $item['text'] !!}</div>
                <div class="blocks-item-image">
                    <a href="{{get_interlink($item)['link'] ?? ''}}" target="_blank">
                        <img src="{{get_image_uri($item['image'])}}" alt="">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
