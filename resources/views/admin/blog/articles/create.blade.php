@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item">{{ __('Blog') }}</li>
            <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">{{ __('Articles') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <form class="form-horizontal" method="POST" action="{{ route('articles.store') }}">
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
                                    aria-controls="seo" aria-selected="false">SEO</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            {{-- --------------------------- MAIN TAB --------------------------------------- --}}
                            <div class="tab-pane fade show active" id="main" role="tabpanel"
                                aria-labelledby="main-tab">
                                <ul class="nav nav-tabs nav-main-tab" role="tablist">
                                    @foreach ($localizations as $key => $lang)
                                        <li class="nav-item">
                                            <a data-lang="{{ $key }}"
                                                class="nav-link @if (config('translatable.locale') == $key) active @endif"
                                                data-toggle="tab" href="#main_lang_{{ $key }}" role="tab">
                                                <span class="hidden-sm-up"></span> <span
                                                    class="hidden-xs-down"><img src="/images/langs/{{ $key }}.jpg" style="width: 20px" alt="{{ $key }}"> {{ $lang }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <br>
                                <div class="tab-content">
                                    @foreach ($localizations as $key => $catLang)
                                        <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
                                            id="main_lang_{{ $key }}" role="tabpanel">
                                            @include('admin.blog.articles.tabs._main', [
                                                'lang' => $key,
                                                'model' => $model,
                                            ])

                                            {!! Constructor::output(\App\Models\Translations\BlogArticleTranslation::class, $key) !!}
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
                                                <span class="hidden-sm-up"></span> <span
                                                    class="hidden-xs-down"><img src="/images/langs/{{ $key }}.jpg" style="width: 20px" alt="{{ $key }}"> {{ $lang }}</span>
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
                    'route' => 'articles',
                    'permission_prefix' => 'blog_articles',
                    'sections' => [
                        'delete' => [
                            'btn_name' => 'Remove',
                        ],
                        'status' => [
                            'statuses' => $statuses,
                        ],
                        'info' => [
                            'blocks' => [
                                // 'main_category' => [
                                //     'show' => true,
                                // ],
                                // 'categories' => [
                                //     'show' => true,
                                // ],
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
@endsection
