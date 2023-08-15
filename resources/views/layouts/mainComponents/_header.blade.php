<?php
$mainMenu = Menu::get('Main',app()->getLocale());

$currentLang = app()->getLocale();
$langs = config('translatable.locales');
?>
<header>
    <div class="container">
        <div class="logowrp">
            <a href="{{ route('main') }}" class="logo">
                <img src="{{ asset('/img/logo.svg') }}" alt=""></a>
        </div>
        <div class="centerMenu">
            @foreach ($mainMenu as $item)
                @if (count($item['children']) == 0)
                    <a href="{{ $item['url'] }}" class="normalLink mh">{{ $item['name'] }}</a>
                @else
                    <span data-href="#" class="dropDown mh">
                        <a class="normalLink" href="{{ $item['url'] }}">{{ $item['name'] }} <span
                                class="ic-chewrondown"></span></a>
                        <div class="expand">
                            @foreach ($item['children'] as $item2)
                                <a href="{{ $item2['url'] }}">{{ $item2['name']  }}</a>
                            @endforeach
                        </div>
                    </span>
                @endif

            @endforeach
        </div>
        <div class="langWrp">
            <span class="ddCaption">{{ $langs[app()->getLocale()] }} <span class="ic-chewrondown"></span></span>
            <div class="expand">
                @foreach ($langs as $key => $l)
                    @if ($key !== app()->getLocale())
                        <a href="{{ route('setlocale', ['lang' => $key]) }}">{{ $l }}</a>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="mobBurger">
            <a href="#">
                <img src="/img/catalog.svg" alt="">
            </a>
        </div>
    </div>
</header>
