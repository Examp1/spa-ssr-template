@extends('layouts.app')

@section('meta')
    <meta name="description" content="{{ $category->transMetaDescription }}">
    <meta property="og:locale" content="{{app()->getLocale()}}" />
    <meta property="og:title" content="{{ $category->transMetaTitle }}" />
    <meta property="og:description" content="{{ strip_tags($category->transExcerpt) }}" />
    <meta property="og:url" content="{{ \Illuminate\Support\Facades\URL::current() }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="article:published_time" content="{{ \Carbon\Carbon::now()->toIso8601String() }}" />
    <meta property="og:image" content="{{ get_image_uri($category->transImage, 'original') }}" />
@endsection

@section('content')
    <div>
        <section class="blog">
            <div class="container">
                <h1 class="section-h4">{{$category->transName}}</h1>
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
