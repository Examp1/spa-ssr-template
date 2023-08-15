@extends('layouts.app')

@section('meta')
    <meta name="description" content="{{ $page->meta_description }}">
    <meta property="og:locale" content="uk_UA" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ $page->transMetaTitle }}" />
    <meta property="og:description" content="{{ $page->transMetaDescription }}" />
    <meta property="og:url" content="{{ \Illuminate\Support\Facades\URL::current() }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="article:published_time" content="{{ \Carbon\Carbon::now()->toIso8601String() }}" />
    <title>{{$page->transMetaTitle}}</title>
@endsection

@section('content')
    <section class="blogPostSingle">
        <div class="container">
            <div class="headBlogPost">
                <div>
                    <a href="{{ route('blog') }}" class="back"><span class="ic-left"></span>
                        {{ __('back') }}</a>
                    <a href="#" class="soc"><span class="ic-faceb"></span></a>
                    <a href="#" class="soc"><span class="ic-wtitter"></span></a>
                    <a href="#" class="soc"><span class="ic-link"></span></a>
                </div>
                <div>
                    <span class="date">{{ \Carbon\Carbon::parse($page->public_date)->format('d.m.y') }}</span>
                </div>
            </div>
        </div>
        <div class="imgBannerAndText">
            <img src="{{ get_image_uri($page->transImage, 'original') }}" alt="{{$page->transImageAlt}}" style="width: 300px">
            <h1>{{ $page->transName }}</h1>
        </div>
        <div class="articleDiv">
            @include('front.site.layouts.includes.constructor', ['constructor' =>
            $page->getTranslation(app()->getLocale())->constructor->data, 'catalog' => 'pages::site.components.'])
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .post-linked-in {
            position: relative;
        }

        .copy-link {
            -webkit-transition: .5s;
            -o-transition: .5s;
            transition: .5s;
            opacity: 0;
            position: absolute;
            top: 50px;
            left: -150px;
            background-color: rgba(0, 0, 0, .7);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .visible {
            opacity: 1;
        }

    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.post-linked-in', function(e) {
                e.preventDefault();

                const postUrl = $(this).attr('data-url');
                const input = document.createElement("input");

                input.value = postUrl;
                document.body.appendChild(input);
                input.select();

                try {
                    document.execCommand('copy');
                    $('.copy-link').addClass('visible');

                    function hideAlert() {
                        $('.copy-link').removeClass('visible');
                    }
                    setTimeout(hideAlert, 2000)
                } catch (err) {
                    console.log('Oops, unable to copy');
                }

                document.body.removeChild(input);
            });
        });
    </script>
@endpush
