<div class="widget-accordion">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    @if(isset($data['list']) && count($data['list']))
        <div class="accordion-list">
            @foreach($data['list'] as $row)
                <div class="accordion-item">
                    <div>{{$row['title']}}</div>
                    <div>{!! $row['text'] !!}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
