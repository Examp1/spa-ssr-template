@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('attributes.index') }}">{{ __('Attribute') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ред. {{ $model->name }}</li>
        </ol>
    </nav>

    <form class="form-horizontal" method="POST" action="{{ route('attributes.update', $model->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $model->id }}" />
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
                                    @include('ecom::admin.attributes._form', [
                                        'lang' => $key,
                                        'model' => $model,
                                        'data' => $data,
                                    ])
                                </div>
                            @endforeach

                            <hr>

                            {{-- <div class="form-group row">
                                <label class="col-md-3 text-right" for="group">{{ __('Attributes group') }}</label>
                                <div class="col-md-9" id="group">
                                    <select
                                        class="select2 form-control custom-select{{ $errors->has('attribute_group_id') ? ' is-invalid' : '' }}"
                                        style="width: 100%; height:36px;" name="attribute_group_id" id="group"
                                        style="width: 100%">
                                        @foreach ($groups as $group_id => $group_name)
                                            <option value="{{ $group_id }}"
                                                @if (old('group', $model->attribute_group_id ?? null) == $group_id) selected @endif>{{ $group_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            @php($selected_groups = $model->attributeGroups->pluck('id')->toArray())
                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="group">{{ __('Attributes group') }}</label>
                                <div class="col-md-9" id="attribute_groups">
                                    <select multiple
                                        class="select2 custom-select{{ $errors->has('attribute_groups') ? ' is-invalid' : '' }}"
                                        style="width: 100%;" name="attribute_groups[]" id="attribute_groups">
                                        @foreach ($groups as $group_id => $group_name)
                                            <option value="{{ $group_id }}"
                                                @if (in_array($group_id, $selected_groups)) selected @endif>{{ $group_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

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

                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="type">{{ __('Attributes type') }}</label>
                                <div class="col-md-9" id="type">
                                    <select
                                        class="select2 form-control custom-select{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                        style="width: 100%; height:36px;" name="type" id="type" style="width: 100%">
                                        @foreach (\Owlwebdev\Ecom\Models\Attribute::TYPES as $type)
                                            <option value="{{ $type }}"
                                                @if (old('type', $model->type ?? 'text') == $type) selected @endif>{{ __($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

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

                            <div class="form-group row">
                                <label class="col-md-3 text-right" for="page_slug">Slug</label>
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
                        </div>

                        <input type="submit" value="{{ __('Save') }}" class="btn btn-success text-white float-right">

                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #5897fb !important;
            border: 1px solid #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color: #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            color: #3e5569;
            background-color: #fff;
            border-color: rgba(0, 0, 0, 0.25);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }

        /*-- ==============================================================
                        Switches
                        ============================================================== */
        .material-switch {
            line-height: 3em;
        }

        .material-switch>input[type="checkbox"] {
            display: none;
        }

        .material-switch>label {
            cursor: pointer;
            height: 0px;
            position: relative;
            width: 40px;
        }

        .material-switch>label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position: absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }

        .material-switch>label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }

        .material-switch>input[type="checkbox"]:checked+label::before {
            background: inherit;
            opacity: 0.5;
        }

        .material-switch>input[type="checkbox"]:checked+label::after {
            background: inherit;
            left: 20px;
        }

        .select2-container--classic .select2-selection--single,
        .select2-container--default .select2-selection--multiple,
        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--single .select2-selection__arrow,
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            height: inherit;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2-field").select2();

            $('.select2').select2();

            $(".select2-field-tagable").select2({
                tags: true
            });
        });
    </script>
@endpush
