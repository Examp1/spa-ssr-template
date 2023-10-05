@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('attribute_groups.index') }}">{{ __('Attribute groups') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <form class="form-horizontal" method="POST" action="{{ route('attribute_groups.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($localizations as $key => $lang)
                                <li class="nav-item">
                                    <a class="nav-link @if (app()->getLocale() == $key) active @endif" data-toggle="tab"
                                        href="#main_lang_{{ $key }}" role="tab">
                                        <span class="hidden-sm-up"></span> <span class="hidden-xs-down">
                                            <img src="/images/langs/{{ $key }}.jpg" style="width: 20px"
                                                alt="{{ $key }}">
                                            {{ $lang }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <br>
                        <div class="tab-content">
                            @foreach ($localizations as $key => $catLang)
                                <div class="tab-pane p-t-20 p-b-20  @if (app()->getLocale() == $key) active @endif"
                                    id="main_lang_{{ $key }}" role="tabpanel">
                                    @include('ecom::admin.attribute_groups._form', [
                                        'lang' => $key,
                                        'model' => $model,
                                    ])
                                </div>
                            @endforeach

                            <hr>

                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_order">{{ __('Sort order') }}</label>
                                <div class="col-md-9">
                                    <input type="number" name="order" value="{{ old('order', $model->order ?? '') }}"
                                        id="page_order"
                                        class="form-control{{ $errors->has('order') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('order'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('order') }}</strong>
                                        </span>
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
