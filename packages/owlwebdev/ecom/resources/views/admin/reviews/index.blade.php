@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('reviews.index') }}">{{ __('Reviews') }}</a></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('reviews_create')
                        <a href="{{ route('reviews.create') }}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">

                    <form action="" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-1">
                                <label>{{ __('Statuses') }}</label>
                                <select id="sizesSelect" name="status" class="form-control">
                                    <option value="">---</option>
                                    @foreach($statuses as $key => $status)
                                        <option value="{{ $key }}" @if(request()->input('status') !== null && old('status', request()->input('status')) == $key) selected @endif>{{ $status['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Search') }}</label>
                                <input type="text" name="search" class="form-control" value="{{ old('search', request()->input('search')) }}" placeholder="{{ __('Id') }}, {{ __('Name') }}, {{ __('email') }}, {{ __('Text') }}">
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success form-control">{{ __('Find') }}</button>
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <a href="{{ route('reviews.index') }}" class="btn btn-danger form-control">{{ __('Clear') }}</a>
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
                                <th class="text-center font-weight-bold">{{ __('Name') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Text') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Product') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Status') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Publication date') }}</th>
                                <th class="text-center font-weight-bold" style="min-width: 12em">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($reviews as $model)
                                <tr data-id="{{ $model->id }}">
                                    <td>
                                        <input type="checkbox" class="checkbox-item" data-check="{{ $model->id }}"
                                            name="check[]" value="{{ $model->id }}">
                                    </td>
                                    <td class="text-center">
                                        @can('reviews_edit')
                                            <a href="{{ route('reviews.edit', $model) }}">
                                            {{ $model->id }}
                                        </a>
                                        @else
                                            {{ $model->id }}
                                        @endcan

                                    </td>
                                    <td class="text-center">{{ $model->author }}</td>
                                    <td class="text-center">{{ $model->shortText() }}</td>
                                    <td class="text-center">{{ $model->product ? $model->product->name : '' }}</td>
                                    <td class="text-center">
                                        {{ $model->showStatus() }}

                                    </td>
                                    <td class="text-center">{{ $model->created_at->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('reviews.destroy', $model) }}" method="post">

                                            @csrf
                                            @method('DELETE')

                                            {{-- TODO: Create notify functional --}}
                                            {{-- @if ($model->order_status_id == 1)
                                                <button type="button" data-url="{{ route('reviews.notify-create', ['order' => $model->id]) }}" data-id="{{ $model->id }}" data-target="#c_modal" class="btn btn-info btn-sm ml-1 notify-droped"><i class="far fa-envelope"></i></button>
                                            @endif --}}

                                            @can('reviews_edit')
                                                <a class="btn btn-md btn-primary" href="{{ route('reviews.edit', $model) }}"><i class="fas fa-edit"></i></a>
                                            @endcan

                                            @can('reviews_view')
                                                <a href="{{ $model->product->frontLink(true) }}" target="_blank" class="btn btn-info  btn-md" title="{{ __('Preview') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan

                                            @can('reviews_delete')
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
                                    <td class="text-center" colspan="8">{{ __('No data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <form action="{{ route('reviews.delete-selected') }}" method="post" id="delete_sel_form">
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

                    <div class="mt-5 row">
                        <div class="col-sm-6 col-md-2">
                            Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} entries
                        </div>
                        <div class="col-sm-6 col-md-10">
                            {{ $reviews->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
