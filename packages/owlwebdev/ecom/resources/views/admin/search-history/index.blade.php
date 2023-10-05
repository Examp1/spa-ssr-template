@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('search-history.index') }}">{{ __('Search history') }}</a></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">

                    <form action="" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label>{{ __('Search') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', request()->input('name')) }}" placeholder="{{ __('Search') }}">
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success form-control text-white">{{ __('Find') }}</button>
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <a href="{{ route('search-history.index') }}" class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th class="text-center font-weight-bold">{{ __('ID') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Input text') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Select variant') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Ip') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Date and time') }}</th>
                                <th class="text-center font-weight-bold" style="min-width: 12em">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($model as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>
                                        <input type="checkbox" class="checkbox-item" data-check="{{ $item->id }}"
                                            name="check[]" value="{{ $item->id }}">
                                    </td>
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td class="text-center">{{ $item->input }}</td>
                                    <td class="text-center">{{ $item->select }}</td>
                                    <td class="text-center">{{ $item->ip }}</td>
                                    <td class="text-center">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('search-history.destroy', ['id' => $item->id]) }}" method="post">

                                            @csrf
                                            @method('DELETE')

                                            @can('search_history_delete')
                                                <a href="javascript:void(0)" title="{{ __('Remove') }}"
                                                    class="btn btn-danger btn-md delete-item-btn text-white">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endcan
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="7">{{ __('No data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <form action="{{ route('search-history.delete-selected') }}" method="post" id="delete_sel_form">
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
