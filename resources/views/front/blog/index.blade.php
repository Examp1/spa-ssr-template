@extends('layouts.app')

@section('meta')
<?php
$metaTitle = app(\App\Modules\Setting\Setting::class)->get('blog_meta_title');
$metaDescription = app(\App\Modules\Setting\Setting::class)->get('blog_meta_description');
$pageTitle = app(\App\Modules\Setting\Setting::class)->get('blog_title');
$pageContent = app(\App\Modules\Setting\Setting::class)->get('blog_description');
?>
<meta name="description" content="{{ $metaDescription }}">
<meta property="og:locale" content="{{app()->getLocale()}}" />
<meta property="og:title" content="{{ $metaTitle }}" />
<meta property="og:description" content="{{ $metaDescription }}" />
<meta property="og:url" content="{{ \Illuminate\Support\Facades\URL::current() }}" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />
<title>{{$metaTitle}}</title>
@endsection

@section('content')
    <div>
        <section class="blog">
            <div class="container">
                <h1 class="section-h4">{{$pageTitle}}</h1>
                <div>{!! $pageContent !!}</div>
                <a href="/blog" class="back mb-1">
                    <span class="ic-arrow"></span>
                    <span class="tolltip">Назад</span>
                </a>
                <div class="row" style="margin-top: 30px">
                    @foreach($articles as $item)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="blog-item">
                                <a href="/blog/{{$item->path}}/{{$item->slug}}">
                                    <div class="blog-item-head">
                                        <img src="{{get_image_uri($item->transImage, 'original')}}"
                                             alt="{{$item->transImageAlt}}"
                                             style="width: 100%;"
                                        >
                                    </div>
                                    <p class="blog-item__title">{{$item->transName}}</p>
                                    <hr>
                                    <p class="description">{{ strip_tags($item->transExcerpt) }}</p>

                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $articles->links('layouts._pagination',['paginator' => $articles]) }}
            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
