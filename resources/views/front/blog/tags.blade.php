@extends('layouts.templates.blog_all.app')

@section('content')
    <div class="r-col container-blog">
        <section class="blog">
            <div class="container">
                <h1 class="section-h4">{{$tag->name}}</h1>
                <a href="/blog" class="back mb-1">
                    <span class="ic-arrow"></span>
                    <span class="tolltip">Повернутись до всіх статей</span>
                </a>
                <div class="row" style="margin-top: 30px">

                    @foreach($model as $item)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="blog-item">
                            <a href="/blog/{{$item->slug}}">
                                <div class="blog-item-head">
                                    <img src="{{get_image_uri($item->image_thumb, 'original')}}"
                                         alt="Великий гайд про Річний тариф на газ: що це таке, які переваги та як уникнути проблем">
                                </div>

                                <p class="blog-item__title">{{$item->name}}</p>

                                <hr>
                                <p class="description">{{ strip_tags($item->excerpt) }}</p>

                            </a>
                            <div class="tegs">
                                @if($item->tags)
                                    <a href="/blog/tag/novini-servisu" rel="nofollow">Новини сервісу</a>
                                @endif
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>

                {{ $model->links('layouts._pagination',['paginator' => $model]) }}
            </div>
        </section>
    </div>
@endsection

@push('scripts')

@endpush
