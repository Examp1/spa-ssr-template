<div style="display: flex;justify-content: space-between;width: 70px;">
    @foreach(config('translatable.locales') as $code => $name)
        @if(in_array($code, $langs))
            <img style="width: 20px" src="/images/langs/{{$code}}.jpg">
        @else
            <img style="width: 20px; opacity:0.2" src="/images/langs/{{$code}}.jpg" title="Нет перевода">
        @endif
    @endforeach
</div>
