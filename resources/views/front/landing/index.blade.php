@extends('layouts.' . $page->template)

@section('content')
    <section class="landingSingle">
        <h1>{{$page->getTranslation(app()->getLocale())->title}}</h1>
        <div>
            @if($page->getTranslation(app()->getLocale())->description !== '<p><br></p>')
                {!! $page->getTranslation(app()->getLocale())->description !!}
            @endif
        </div>
        <div>
            @include('front.site.layouts.includes.constructor', ['constructor' => $page->getTranslation(app()->getLocale())->constructor->data, 'catalog' => 'pages::site.components.'])
        </div>
    </section>

@endsection
