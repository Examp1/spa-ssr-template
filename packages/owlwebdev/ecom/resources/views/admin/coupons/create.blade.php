@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('coupons.index') }}">{{ __('Coupons') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
        </ol>
    </nav>

    <form class="form-horizontal" method="POST" action="{{ route('coupons.store') }}">
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
                                    @include('ecom::admin.coupons._form', [
                                        'lang' => $key,
                                        'model' => $model,
                                    ])
                                </div>
                            @endforeach

                            <hr>

                            {{-- <div class="form-group row">
                                <label class="col-md-3 text-right" for="icon">{{ __('Іконка') }}</label>
                                <div class="col-md-9">
                                    @include('ecom::admin.pieces.fields.select-options-with-icons', [
                                        'field_title' => __('Іконка'),
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
                            {{-- <input type="hidden" name="type" value="percent" /> --}}
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_type">{{ __('Type') }}</label>
                                <div class="col-md-9">
                                    <select name="type">
                                        @foreach ($discount_types as $type => $type_name)
                                            <option value="{{ $type }}">{{ __($type_name) }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('type'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_value">{{ __('Value') }}</label>
                                <div class="col-md-9">
                                    <input type="number" name="value" value="{{ old('value', $model->value ?? '') }}"
                                        id="page_value"
                                        class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('value'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('value') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_quantity">{{ __('Quantity') }}</label>
                                <div class="col-md-9">
                                    <input type="number" name="quantity"
                                        value="{{ old('quantity', '') }}" id="page_quantity"
                                        class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('quantity'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('quantity') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_slug">{{ __('Code') }}</label>
                                <div class="col-md-9">
                                    <input type="text" name="slug" value="{{ old('slug', $model->slug ?? '') }}"
                                        id="page_slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('slug'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('slug') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_start_at">{{ __('Start date') }}</label>
                                <div class="col-md-9">
                                    <input type="text" name="start_at"
                                        value="{{ old('start_at', $model->start_at ?? '') }}" id="page_start_at"
                                        class="form-control{{ $errors->has('start_at') ? ' is-invalid' : '' }} date">

                                    @if ($errors->has('start_at'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('start_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_end_at">{{ __('End date') }}</label>
                                <div class="col-md-9">
                                    <input type="text" name="end_at" value="{{ old('end_at', $model->end_at ?? '') }}"
                                        id="page_end_at"
                                        class="form-control{{ $errors->has('end_at') ? ' is-invalid' : '' }}">

                                    @if ($errors->has('end_at'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('end_at') }}</strong>
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

@push('styles')
    <link rel="stylesheet" href="{{ asset('matrix/css/icons/font-awesome/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">
@endpush

@push('scripts')
    <script src="{{ asset('matrix/libs/moment/moment.js') }}"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script>
        $(document).ready(() => {
            $('#page_start_at').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: true,
                icons: {
                    time: "fa fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: "fa fa-chevron-left",
                    next: "fa fa-chevron-right",
                    today: "fa fa-clock",
                    clear: "fa fa-trash"
                }
            });
            $('#page_end_at').datetimepicker({
                format: 'YYYY-MM-DD HH:mm:ss',
                useCurrent: true,
                icons: {
                    time: "fa fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down",
                    previous: "fa fa-chevron-left",
                    next: "fa fa-chevron-right",
                    today: "fa fa-clock",
                    clear: "fa fa-trash"
                }
            });
        });
    </script>
@endpush
