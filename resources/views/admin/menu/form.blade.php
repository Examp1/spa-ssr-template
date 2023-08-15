<?php
use App\Models\Menu;

/* @var $model Menu */
/* @var $errors array */

?>

@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('menu.index') }}">{{ __('Menu') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
        </ol>
    </nav>

    <form action="{{ route('menu.update', $model->id) }}" method="post" class="form-horizontal">
        @method('PUT')
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($localizations as $key => $lang)
                                <li class="nav-item">
                                    <a class="nav-link @if (config('translatable.locale') == $key) active @endif" data-toggle="tab"
                                        href="#main_lang_{{ $key }}" role="tab">
                                        <span class="hidden-sm-up"></span> <span class="hidden-xs-down"><img
                                                src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                alt="{{ $key }}"> {{ $lang }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <br>
                        <div class="tab-content">
                            @foreach ($localizations as $key => $catLang)
                                <div class="tab-pane p-t-20 p-b-20  @if (config('translatable.locale') == $key) active @endif"
                                    id="main_lang_{{ $key }}" role="tabpanel">

                                    <div class="form-group row">
                                        <label class="col-md-3 text-right"
                                            for="page_name_{{ $key }}">{{ __('Title') }}</label>
                                        <div class="col-md-9">
                                            <input type="text" name="page_data[{{ $key }}][name]"
                                                value="{{ old('page_data.' . $key . '.name', $data[$key]['name'] ?? '') }}"
                                                id="page_name_{{ $key }}"
                                                class="form-control{{ $errors->has('page_data.' . $key . '.name') ? ' is-invalid' : '' }}">

                                            @if ($errors->has('page_data.' . $key . '.name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('page_data.' . $key . '.name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($model->type == \App\Models\Menu::TYPE_ARBITRARY)
                                        <div class="form-group row">
                                            <label class="col-md-3 text-right"
                                                for="page_url_{{ $key }}">{{ __('Link') }}</label>
                                            <div class="col-md-9">
                                                <input type="text" name="page_data[{{ $key }}][url]"
                                                    value="{{ old('page_data.' . $key . '.url', $data[$key]['url'] ?? '') }}"
                                                    id="page_url_{{ $key }}"
                                                    class="form-control{{ $errors->has('page_data.' . $key . '.url') ? ' is-invalid' : '' }}">

                                                @if ($errors->has('page_data.' . $key . '.url'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('page_data.' . $key . '.url') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="field_icon">Иконка</label>
                                <div class="col-md-9">
                                    <input type="text" name="icon" value="{{ old('icon', $model->icon ?? '') }}"
                                        id="field_icon"
                                        class="form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('icon'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('icon') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <?php
                            switch ($model->type) {
                                case Menu::TYPE_PAGE:
                                    $entity = \App\Models\Pages::query()
                                        ->where('id', $model->model_id)
                                        ->first();
                                    $url = \App\Models\Pages::backLink($entity->id);
                                    break;
                                case Menu::TYPE_BLOG:
                                    $entity = \App\Models\BlogArticles::query()
                                        ->where('id', $model->model_id)
                                        ->first();
                                    $url = \App\Models\BlogArticles::backLink($entity->id);
                                    break;
                                case Menu::TYPE_BLOG_CATEGORY:
                                    $entity = \App\Models\BlogCategories::query()
                                        ->where('id', $model->model_id)
                                        ->first();
                                    $url = \App\Models\BlogCategories::backLink($entity->id);
                                    break;
                                case Menu::TYPE_LANDING:
                                    $entity = \App\Models\Landing::query()
                                        ->where('id', $model->model_id)
                                        ->first();
                                    $url = \App\Models\Landing::backLink($entity->id);
                                    break;
                            }
                            ?>

                            <div class="form-group row">
                                <label class="col-md-3"></label>
                                <div class="col-md-9">
                                    @if ($model->type != \App\Models\Menu::TYPE_ARBITRARY)
                                        <a href="{{ $url??'' }}" target="_blank">
                                            {{ \App\Models\Menu::getTypes()[$model->type] }}
                                            -
                                            @if(isset($entity))
                                                <b>{{ $entity->getTranslation(config('translatable.locale'))->title ?? $entity->getTranslation(config('translatable.locale'))->name }}</b>
                                            @endif
                                    @else
                                        <span>{{ \App\Models\Menu::getTypes()[$model->type] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <input type="submit" value="{{ __('Save') }}" class="btn btn-success text-white float-right">

                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
