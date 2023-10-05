@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('filters.index') }}">{{ __('Filters') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Editing') }} {{ $attribute_group->translate()->name }}</li>
        </ol>
    </nav>

    <form class="form-horizontal" method="POST" action="{{ route('filters.update', $attribute_group->id) }}">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Attribute') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Expanded') }}</th>
                                    <th>{{ __('Sort order') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($models as $item)
                                    <tr data-id="{{ $item->filter_id }}">
                                        <td>
                                            <input type="hidden" name="slug[]" value="{{ $item->slug }}">
                                            <input type="hidden" name="attribute_id[]" value="{{ $item->id }}">
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            <select id="display" name="display[]"
                                                class="select2 form-control custom-select"
                                                style="width: 100%; height:36px;">
                                                @foreach ($types as $type => $name)
                                                    <option value="{{ $type }}"
                                                        @if ($item->display == $type) selected @endif>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="checkbox" value="1" name="expanded[]"
                                                {{ $item->expanded == 1 ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="order[]"
                                                value="{{ old('order', $item->order) }}">
                                        </td>

                                        <td style="width: 150px">
                                            <div style="display: flex">
                                                <div style="margin-left: 10px">
                                                    @can('attributes_edit')
                                                        <a href="{{ route('attributes.edit', ['attribute' => $item->id]) }}"
                                                            class="btn btn-md btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

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

            $(".select2-field-tagable").select2({
                tags: true
            });
        });
    </script>
@endpush
