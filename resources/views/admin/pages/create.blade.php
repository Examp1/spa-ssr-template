@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">{{ __('Pages') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <form class="form-horizontal" method="POST" action="{{ route('pages.store') }}">
        @csrf

        <input type="hidden" id="tab_lang" name="tab_lang" value="{{request()->get('lang',config('translatable.locale'))}}">

        <div class="row">
            <div class="col-md-9">
                <div class="card">

                    <div class="card-header">
                        <ul class="nav nav-tabs" id="myTab" role="tablist"
                            style="margin-bottom: -10px;border-bottom: none">
                            <li class="nav-item">
                                <a class="nav-link active" id="main-tab" data-toggle="tab" href="#main" role="tab"
                                    aria-controls="main" aria-selected="true">{{ __('Info') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab"
                                    aria-controls="seo" aria-selected="false">SEO</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            {{-- --------------------------- MAIN TAB --------------------------------------- --}}
                            <div class="tab-pane fade show active" id="main" role="tabpanel"
                                aria-labelledby="main-tab">
                                <ul class="nav nav-tabs nav-main-tab nav-lang-tab" role="tablist">
                                    @foreach ($localizations as $key => $lang)
                                        <li data-lang="{{ $key }}" class="nav-item">
                                            <a data-lang="{{ $key }}"
                                                class="nav-link @if (config('translatable.locale') == $key) active @endif"
                                                data-toggle="tab" href="#main_lang_{{ $key }}" role="tab">
                                                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                                                        src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                        alt="{{ $key }}"> {{ $lang }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <br>
                                <div class="tab-content tab-content-lang">
                                    @foreach ($localizations as $key => $catLang)
                                        <div data-lang="{{$key}}" class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
                                            id="main_lang_{{ $key }}" role="tabpanel">
                                            @include('admin.pages.tabs._main', [
                                                'lang' => $key,
                                                'model' => $model,
                                            ])

                                            {!! Constructor::output(\App\Models\Translations\PageTranslation::class, $key) !!}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- --------------------------- SEO TAB --------------------------------------- --}}
                            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    @foreach ($localizations as $key => $lang)
                                        <li class="nav-item">
                                            <a class="nav-link @if (config('translatable.locale') == $key) active @endif"
                                                data-toggle="tab" href="#seo_lang_{{ $key }}" role="tab">
                                                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                                                        src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                        alt="{{ $key }}"> {{ $lang }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <br>
                                <div class="tab-content tabcontent-border">
                                    @foreach ($localizations as $key => $catLang)
                                        <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
                                            id="seo_lang_{{ $key }}" role="tabpanel">
                                            @include('admin.pages.tabs._seo', [
                                                'lang' => $key,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <?php
                $statuses = [];
                foreach ($localizations as $key => $catLang) {
                    $statuses[] = [
                        'tooltip' => __('Language version status') . ' (' . config('translatable.locale_codes')[$key] . ')',
                        'title' => __('Status') . ' ' . config('translatable.locale_codes')[$key],
                        'field_name' => 'page_data[' . $key . '][status_lang]',
                    ];
                }

                $statuses = array_merge(config('asider.sections.status.statuses'), $statuses);
                ?>

                @include('layouts.admin.components._asider', [
                    'route' => 'pages',
                    'permission_prefix' => 'pages',
                    'sections' => [
                        'status' => [
                            'statuses' => $statuses,
                        ],
                        'delete' => [
                            'btn_name' => 'Remove',
                        ],
                    ],
                ])
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $(".nav-lang-tab .nav-item").on('click',function(){
                let lang = $(this).data('lang');
                var currentUrl = window.location.href;
                var paramName = "lang";
                var paramRegex = new RegExp('([?&])' + paramName + '=[^&]*');
                if (paramRegex.test(currentUrl)) {
                    var newUrl = currentUrl.replace(paramRegex, '$1' + paramName + '=' + lang);
                } else {
                    var separator = currentUrl.includes('?') ? '&' : '?';
                    var newUrl = currentUrl + separator + paramName + '=' + lang;
                }
                window.history.pushState({}, "", newUrl);
                $("#tab_lang").val(lang);
            });

            var queryString = window.location.search;
            var urlParams = new URLSearchParams(queryString);
            var langValue = urlParams.get('lang');

            if(langValue){
                $(".nav-lang-tab .nav-item a").removeClass('active');
                $(".nav-lang-tab .nav-item a[data-lang='"+langValue+"']").addClass('active');

                $(".tab-content-lang .tab-pane").removeClass('active');
                $(".tab-content-lang .tab-pane[data-lang='"+langValue+"']").addClass('active');
            }
        });
    </script>
@endpush
