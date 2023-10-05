@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Products') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-exportable">
                <div class="card-header">
                    @can('products_create')
                        <a href="{{ route('products.create') }}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <form id="filter" action="" method="get">
                        <div class="filter-container">
                            <div>
                                <label>{{ __('Search') }}</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', request()->input('name')) }}">
                                    <div class="input-group-append">
                                        <select name="search_lang" class="form-control">
                                            @foreach (config('translatable.locale_codes') as $langKey => $item)
                                                <option value="{{ $langKey }}"
                                                    @if (request()->input('search_lang') == $langKey) selected @endif>{{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Category') }}</label>
                                <select name="categories[]" multiple="multiple" class="select2 form-control custom-select m-t-15">
                                    <option value="">{{ __('Any') }}</option>
                                    {!!\Owlwebdev\Ecom\Models\Category::getOptionsHTML(request()->input('categories')) !!}
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="select2 form-control custom-select m-t-15">
                                    <option value="" >{{ __('All') }}</option>
                                    <option value="1"@if (old('status', request()->input('status')) == '1') selected @endif>{{ __('Active entries') }}({{ $active_count }})</option>
                                    <option value="0"@if (old('status', request()->input('status')) === '0') selected @endif>{{ __('Inactive entries') }}({{ $notActive_count }})</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Availability') }}</label>
                                <select name="quantity" class="select2 form-control custom-select m-t-15">
                                    <option value="" >{{ __('All') }}</option>
                                    <option value="1"@if (old('quantity', request()->input('quantity')) == '1') selected @endif>{{ __('Quantiy +') }}</option>
                                    <option value="0"@if (old('quantity', request()->input('quantity')) == '0') selected @endif>{{ __('Quantiy -') }}</option>
                                    <option value="10"@if (old('quantity', request()->input('quantity')) == '10') selected @endif>{{ __('Only :q left', ['q' => 10]) }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="inputPassword4">&nbsp;</label>
                                <button type="submit"
                                    class="btn btn-success form-control text-white">{{ __('Filter') }}</button>
                            </div>
                            <div>
                                <label for="inputPassword4">&nbsp;</label>
                                <a href="{{ route('products.index') }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                        {{-- <input type="hidden" name="status" value="{{ old('status', request()->input('status')) }}">
                        <a href="javascript:void(0)" class="set-filter-status" data-status="1">{{ __('Active entries') }}
                            ({{ $active_count }})</a>
                        <a href="javascript:void(0)" class="set-filter-status" data-status="0"
                            style="margin-left: 10px">{{ __('Inactive entries') }}
                            ({{ $notActive_count }})</a> --}}
                    </form>

                    <table class="table table-bordered imSortingTableLib">
                        <thead>
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th style="width: 70px" class="sorting @if (request()->input('sort') == 'id' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'id' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="id">{{ __('ID') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'name' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'name' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="name">{{ __('Title') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'status' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'status' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="status">{{ __('Status') }}</th>
                                <th style="width: 100px">{{ __('Languages') }}</th>
                                <th style="width: 60px">{{ __('Meta-tags') }}</th>
                                {{-- JIRA --}}
                                {{-- дата створення - убрать в категории и в продукте  и убрал на строке 104 --}}
                                {{-- <th class="sorting @if (request()->input('sort') == 'created_at' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'created_at' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="created_at">{{ __('Publication date') }}</th> --}}
                                <th style="width: 210px">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($model as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>
                                        <input type="checkbox" class="checkbox-item" data-check="{{ $item->id }}"
                                            name="check[]" value="{{ $item->id }}">
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @can('products_edit')
                                            <a href="{{ route('products.edit', $item->id) }}">
                                                {{ $item->name }}
                                            </a>
                                        @else
                                            {{ $item->name }}
                                        @endcan
                                    </td>
                                    <td>{{ $item->categoriesToString() }}</td>
                                    <td>{!! $item->showStatus() !!}</td>
                                    <td>{!! $item->showAllLanguagesNotEmpty() !!}</td>
                                    <td>{!! $item->showMetaInfo() !!}</td>
                                    {{-- <td>{{ $item->created_at->format('d.m.Y H:i') }}</td> --}}
                                    <td>
                                        <form action="{{ route('products.destroy', $item->id) }}" method="POST">

                                            @if (isset($item->categories[0]->path))
                                                <a href="{{ $item->frontLink(true) }}" target="_blank"
                                                    title="{{ __('Preview') }}" class="btn btn-info  btn-md"><i
                                                        class="fa fa-eye"></i></a>
                                            @else
                                                <a href="javascript:void(0)" title=""
                                                    class="btn btn-danger text-white btn-md"><i
                                                        class="fa fa-eye"></i></a>
                                            @endif

                                            @can('products_edit')
                                                <a href="{{ route('products.edit', $item->id) }}"
                                                    class="btn btn-md btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @can('products_edit')
                                                <a href="{{ route('products.copy', ['id' => $item->id]) }}"
                                                    class="btn btn-dark btn-md" title="{{ __('Copy') }}"><i
                                                        class="fa fa-copy"></i></a>
                                            @endif

                                            @csrf
                                            @method('DELETE')

                                            @can('products_delete')
                                                <a href="javascript:void(0)" title="{{ __('Remove') }}"
                                                    class="btn btn-danger btn-md delete-item-btn text-white">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <form action="{{ route('products.delete-selected') }}" method="post" id="delete_sel_form">
                        @csrf
                        <input type="hidden" name="ids">
                    </form>
                    <div class="row">
                        <div class="col-sm-12">
                            <label style="margin-right: 15px">{{ __('With selected:') }}</label>

                            <span class="fa fa-trash btn alert-danger btn-xs btn-delete-checked"
                                title="{{ __('Remove') }}"></span>

                        </div>
                    </div>

                    {{ $model->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
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

        .select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color:#5897fb !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            color: #3e5569;
            background-color: #fff;
            border-color: rgba(0,0,0,0.25);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }

        .select2-container--default .select2-selection--multiple {
            height: inherit;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/js/im-export.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            @can('products_edit')
                var isMakeExport = true;
            @else
                var isMakeExport = false;
            @endcan
            if (isMakeExport) {
                let exportLib = new imExportTableLib({
                    cardQuerySelector: ".card-exportable",
                    dbTable: "products"
                });
            }
        });
    </script>
@endpush
