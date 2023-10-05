@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Coupons') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('coupons_create')
                        <a href="{{ route('coupons.create') }}" class="btn btn-success text-white float-right">
                            <i class="fa fa-plus"></i>
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <table class="table table-bordered imSortingTableLib">
                        <thead>
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th class="sorting @if (request()->input('sort') == 'name' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'name' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="name">{{ __('Name') }}({{ __('Value') }})</th>
                                <th class="sorting @if (request()->input('sort') == 'slug' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'slug' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="slug">{{ __('Code') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'start_at' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'start_at' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="start_at">{{ __('Start at') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'end_at' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'end_at' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="end_at">{{ __('End at') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'status' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'status' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="status">{{ __('Status') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'created_at' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'created_at' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="created_at">{{ __('Created at') }}</th>
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
                                    <td>@can('coupons_edit')
                                            <a href="{{ route('coupons.edit', $item->id) }}">
                                                {{ $item->name }}(-{{ $item->type == 'fixed' ? $item->value : $item->value . '%' }})
                                            </a>
                                        @else
                                            {{ $item->name }}( -{{ $item->type == 'fixed' ? $item->value : $item->value . '%' }})
                                        @endcan
                                    </td>
                                    <td>{{ $item->slug }}</td>
                                    <td>{{ $item->start_at ? $item->start_at->format('d.m.Y') : '-' }}</td>
                                    <td>{{ $item->end_at ? $item->end_at->format('d.m.Y') : '-' }}</td>
                                    <td>{!! $item->showStatus() !!}</td>
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        <div style="display: flex">
                                            <div style="margin-left: 10px">
                                                <form action="{{ route('coupons.destroy', $item->id) }}" method="POST">

                                                    @can('coupons_edit')
                                                        <a href="{{ route('coupons.edit', $item->id) }}"
                                                            class="btn btn-md btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')

                                                    @can('coupons_delete')
                                                        <a href="javascript:void(0)" title="{{ __('Remove') }}"
                                                            class="btn btn-danger btn-md delete-item-btn text-white">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    @endcan
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <form action="{{ route('coupons.delete-selected') }}" method="post" id="delete_sel_form">
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
