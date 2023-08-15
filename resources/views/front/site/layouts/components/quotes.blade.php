<div class="quoteWrp">
    @if (!empty($content['title']))
        <h3>{{ $content['title'] }}</h3>
    @endif
    <div class="quote">
        {!! $content['text'] !!}
    </div>
</div>
