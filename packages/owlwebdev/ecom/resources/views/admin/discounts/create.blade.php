@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('discounts.index') }}">{{ __('Discounts') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <form class="form-horizontal" method="POST" action="{{ route('discounts.store') }}">
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
                                <div class="tab-pane p-t-20 p-b-20  @if (app()->getLocale() == $key) active @endif"
                                    id="main_lang_{{ $key }}" role="tabpanel">
                                    @include('ecom::admin.discounts._form', [
                                        'lang' => $key,
                                        'model' => $model,
                                    ])
                                </div>
                            @endforeach

                            <hr>

                            {{-- <div class="form-group row">
                                <label class="col-md-3 text-right" for="icon">{{ __('Иконка') }}</label>
                                <div class="col-md-9">
                                    @include('ecom::admin.pieces.fields.select-options-with-icons', [
                                        'field_title' => __('Иконка'),
                                        'field_name' => 'icon',
                                        'input_name' => 'icon',
                                    ])

                                    @if ($errors->has('icon'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('icon') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_percentage">{{ __('Value') }}</label>
                                <div class="col-md-9">
                                    <input type="number" name="percentage"
                                        value="{{ old('percentage', $model->percentage ?? '') }}" id="page_percentage"
                                        class="form-control{{ $errors->has('percentage') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('percentage'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('percentage') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_status">{{ __('Status') }}</label>
                                <div class="col-md-9">
                                    <div class="material-switch pull-left">
                                        <input name="status" value="0" type="hidden" />
                                        <input id="someSwitchOptionSuccess" name="status" value="1" type="checkbox"
                                            {{ old('status', $model->status) ? ' checked' : '' }} />
                                        <label for="someSwitchOptionSuccess" class="label-success"></label>
                                    </div>

                                    @if ($errors->has('status'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('status') }}</strong>
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
