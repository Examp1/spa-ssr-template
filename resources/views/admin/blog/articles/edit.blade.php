@extends('layouts.admin.app')

@section('content')
    <div class="top_textWrp">
        <h1> {{ $model->name }} </h1>
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
                <li class="breadcrumb-item">{{ __('Blog') }}</li>
                <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">{{ __('Articles') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
            </ol>
        </nav>
    </div>

    <form class="form-horizontal" method="POST" action="{{ route('articles.update', $model->id) }}">
        @method('PUT')
        @csrf
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
                                    aria-controls="seo" aria-selected="false">SEO {!! $model->isEmptyMeta(config('translatable.locale')) ? '<span style="color:red">!</span>' : '' !!}</a>
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
                                        <li  data-lang="{{ $key }}" class="nav-item">
                                            <a data-lang="{{ $key }}"
                                                class="nav-link @if (config('translatable.locale') == $key) active @endif"
                                                data-toggle="tab" href="#main_lang_{{ $key }}" role="tab">
                                                <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                                                        src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                        alt="{{ $key }}"> {{ $lang }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#copy_lang" role="tab"
                                            title="Копіювати">
                                            <i class="fa fa-clone" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                </ul>

                                <br>
                                <div class="tab-content tab-content-lang">
                                    @foreach ($localizations as $key => $catLang)
                                        <div data-lang="{{ $key }}" class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
                                            id="main_lang_{{ $key }}" role="tabpanel">
                                            @include('admin.blog.articles.tabs._main', [
                                                'lang' => $key,
                                                'data' => $data,
                                                'model' => $model,
                                            ])
                                            {!! Constructor::output($model->getTranslation($key), $key) !!}
                                        </div>
                                    @endforeach
                                    <div class="tab-pane p-t-20 p-b-20" id="copy_lang" role="tabpanel">
                                        @include('admin.pieces._copy-lang', [
                                            'model_id' => $model->id,
                                            'route' => route('articles.copy-lang'),
                                        ])
                                    </div>
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
                                            @include('admin.blog.articles.tabs._seo', [
                                                'lang' => $key,
                                                'data' => $data,
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
                        'value' => $data[$key]['status_lang'] ?? 0,
                    ];
                }

                $statuses = array_merge(config('asider.sections.status.statuses'), $statuses);
                ?>

                @include('layouts.admin.components._asider', [
                    'route' => 'articles',
                    'permission_prefix' => 'blog_articles',
                    'sections' => [
                        'status' => [
                            'statuses' => $statuses,
                        ],
                        'delete' => [
                            'btn_name' => 'Remove',
                        ],
                        'info' => [
                            'blocks' => [
                                'main_category' => [
                                    'show' => true,
                                ],
                                'categories' => [
                                    'show' => true,
                                ],
                                'tags' => [
                                    'show' => true,
                                ],
                                'public_date' => [
                                    'show' => true,
                                ],
                            ],
                        ],
                    ],
                ])
            </div>
        </div>
    </form>

    @can('blog_articles_delete')
        <form action="{{ route('articles.destroy', $model->id) }}" method="POST" class="delete-model-form">
            @csrf
            @method('DELETE')
        </form>
    @endcan
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
