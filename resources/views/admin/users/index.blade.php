@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Users') }}</li>
            @can('users_create')
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i>
                    {{ __('Create') }}
                </a>
            @endcan
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-exportable">
                <div class="card-body">
                    <form action="" method="get">
                        <div class="filter-container">
                            <div>
                                <label>{{ __('Search') }}</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', request()->input('name')) }}" placeholder="{{ __('Phone') }}, {{ __('Name') }}, {{ __('email') }}">
                                </div>
                            </div>

                            <div>
                                <label >&nbsp;</label>
                                <button type="submit"
                                    class="btn btn-success form-control text-white">{{ __('Find') }}</button>
                            </div>
                            <div>
                                <label>&nbsp;</label>
                                <a href="{{ route('users.index') }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered imSortingTableLib">
                        <thead>
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th>
                                    {{ __('ID') }}
                                </th>
                                <th class="sorting @if (request()->input('sort') == 'name' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'name' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="name">{{ __('Name') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'email' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'email' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="email">Email</th>
                                <th data-field="phone">{{ __('Phone') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr data-id="{{ $user->id }}">
                                    <td>
                                        <input type="checkbox" class="checkbox-item" data-check="{{ $user->id }}"
                                            name="check[]" value="{{ $user->id }}">
                                    </td>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        @can('users_edit')
                                            <a href="{{ route('users.edit', $user->id) }}" title="Редагувати">
                                                {{ $user->name }}
                                            </a>
                                        @else
                                            {{ $user->name }}
                                        @endcan
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        {{ $user->phone }}
                                    </td>
                                    <td>
                                        <div style="display: flex">
                                            <div style="margin-left: 10px">
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">

                                                    @can('users_edit')
                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="btn btn-primary text-white btn-md" title="Редагувати">
                                                            <i class="fas fa-edit "></i>
                                                        </a>
                                                    @endcan
                                                    &nbsp;
                                                    @csrf
                                                    @method('DELETE')

                                                    @can('users_delete')
                                                        @if ($user->id != \Illuminate\Support\Facades\Auth::user()->id)
                                                            <a href="javascript:void(0)" title="{{ __('Remove') }}"
                                                                class="btn btn-danger delete-item-btn text-white btn-md">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center" colspan="5">{{ __('No data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <form action="{{ route('users.delete-selected') }}" method="post" id="delete_sel_form">
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

                    {{ $users->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        nav.breadcrumb-nav {
            position: relative;
        }

        nav.breadcrumb-nav a.btn {
            position: absolute;
            right: 15px;
            top: 4px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('/myLib/im-export-table-lib.js') }}"></script>
    <script>
        $(document).ready(() => {
            var isMakeExport = true;
            if (isMakeExport) {
                let exportLib = new imExportTableLib({
                    cardQuerySelector: ".card-exportable",
                    dbTable: "users"
                });
            }
            $('.delete-item-btn').on('click', function() {
                let _this = $(this);
                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: "{{ __('Are you trying to delete an entry?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('Yes') }}',
                    cancelButtonText: '{{ __('No') }}'
                }).then((result) => {
                    if (result.value) {
                        _this.closest('form').submit();
                    }
                });
            });
        });
    </script>
@endpush
