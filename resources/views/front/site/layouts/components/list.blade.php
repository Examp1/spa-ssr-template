@if(! empty($content['title']))
    <h3>{!! $content['title'] !!}</h3>
@endif

@if($content['type'] == 'ul')
<ul>
    @foreach($content['list'] as $item)
        <li><p>{!! $item['item'] !!}</p></li>
    @endforeach
</ul>
@elseif($content['type'] == 'ol')
<ol>
    @foreach($content['list'] as $item)
        <li><p>{!! $item['item'] !!}</p></li>
    @endforeach
</ol>
@elseif($content['type'] == 'ol_style')
    <ol class="olStages">
        @foreach($content['list'] as $item)
            <li><p>{!! $item['item'] !!}</p></li>
        @endforeach
    </ol>
@endif
