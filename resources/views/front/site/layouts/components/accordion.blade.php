<div class="section-accordion">
    @if (!empty($content['title']))
        <div class="title">{{ $content['title'] }}</div>
    @endif
</div>

@if(isset($content['list']) && count($content['list']))
    <div class="accordion-list">
        @foreach($content['list'] as $item)
            <div class="item">
                <div class="accordion-item-title">{{$item['title']}}</div>
                <div class="accordion-item-text">{!! $item['text'] !!}</div>
            </div>
        @endforeach
    </div>
@endif
