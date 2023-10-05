<input type="hidden" name="_tab" value="{{ $tab }}">

<ul class="nav nav-tabs" role="tablist">
    @foreach ($localizations as $key => $lang)
        <li class="nav-item">
            <a class="nav-link @if (config('translatable.locale') == $key) active @endif" data-toggle="tab"
                href="#main_lang_{{ $key }}" role="tab">
                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                        src="/images/langs/{{ $key }}.jpg" style="width: 20px" alt="{{ $key }}">
                    {{ $lang }}</span>
            </a>
        </li>
    @endforeach
</ul>

<br>

<div class="tab-content">
    @foreach ($localizations as $key => $catLang)
        <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
            id="main_lang_{{ $key }}" role="tabpanel">
            @include('admin.settings.tabs._generate', ['generate_route' => route('pages.meta-generate')])
        </div>
    @endforeach
</div>

<?php $defaultLang = config('translatable.locale'); ?>

