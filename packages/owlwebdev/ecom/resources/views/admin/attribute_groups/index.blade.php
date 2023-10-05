@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Attribute groups') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('attribute_groups_create')
                        <a href="{{ route('attribute_groups.create') }}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">

                    @if (count($model))
                        <form action="" method="get">
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

                            <div>
                                <label for="inputPassword4">&nbsp;</label>
                                <button type="submit"
                                    class="btn btn-success form-control text-white">{{ __('Filter') }}</button>
                            </div>
                            <div>
                                <label for="inputPassword4">&nbsp;</label>
                                <a href="{{ route('attribute_groups.index') }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                        </form>
                    @endif

                    <table class="table table-bordered imSortingTableLib">
                        <thead>
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th class="sorting @if (request()->input('sort') == 'id' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'id' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="id">{{ __('ID') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'name' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'name' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="name">{{ __('Title') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'order' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'order' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="status">{{ __('Sort order') }}</th>
                                <th>{{ __('Languages') }}</th>
                                <th>{{ __('Actions') }}</th>
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
                                        @can('attribute_groups_edit')
                                            <a href="{{ route('attribute_groups.edit', $item->id) }}">
                                                {{ $item->name }}
                                            </a>
                                        @else
                                            {{ $item->name }}
                                        @endcan
                                    </td>
                                    <td>
                                        <form action="{{ route('attribute_groups.sort') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <input type="number" class="form-control" title="Для збереження значення сортування натисніть Enter" name="order"
                                                value="{{ $item->order }}">
                                        </form>
                                    </td>
                                    <td>
                                        @include('admin.pieces.active-languages', [
                                            'langs' => $item->getAllLanguagesNotEmpty()
                                        ])
                                    </td>
                                    <td>
                                        <form action="{{ route('attribute_groups.destroy', $item->id) }}"
                                            method="POST">

                                            @can('attribute_groups_edit')
                                            {{-- JIRA --}}
                                            {{-- сделал кнопки больше --}}
                                                <a href="{{ route('attribute_groups.edit', $item->id) }}"
                                                    class="btn btn-md btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan

                                            @csrf
                                            @method('DELETE')

                                            @can('attribute_groups_delete')
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

                    <form action="{{ route('attribute_groups.delete-selected') }}" method="post" id="delete_sel_form">
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

@push('scripts')
    <script>
        $(document).ready(() => {

        });
    </script>
@endpush