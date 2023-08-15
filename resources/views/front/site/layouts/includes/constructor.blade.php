@if (!empty($constructor))
    @foreach($constructor as $section)
        @if(isset($section['component']))
            @if (view()->exists('front.site.layouts.components.' . $section['component']) && !empty($section['visibility']))
                @include('front.site.layouts.components.' . $section['component'], ['content' => $section['content']])
            @elseif (isset($catalog) && view()->exists($catalog . $section['component']) && !empty($section['visibility']))
                @include($catalog . $section['component'], ['content' => $section['content']])
            @endif
        @endif
    @endforeach
@endif
