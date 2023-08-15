@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Admins') }}</li>
            @can('admins_create')
                <a href="{{ route('admins.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i>
                    {{ __('Create') }}
                </a>
            @endcan
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered imSortingTableLib">
                        <thead>
                            <tr>
                                <th class="sorting @if (request()->input('sort') == 'name' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'name' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="name">{{ __('Name') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'email' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'email' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="email">Email</th>
                                <th>{{ __('Roles') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr data-id="{{ $user->id }}">
                                    <td>
                                        @can('admins_edit')
                                            <a href="{{ route('admins.edit', $user->id) }}" title="Редагувати">
                                                {{ $user->name }}
                                            </a>
                                        @else
                                            {{ $user->name }}
                                        @endcan
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge"
                                                style="color: white; background-color: #009688">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div style="display: flex">
                                            <div style="margin-left: 10px">
                                                <form action="{{ route('admins.destroy', $user->id) }}" method="POST">

                                                    @can('admins_edit')
                                                        <a href="{{ route('admins.edit', $user->id) }}"
                                                            class="btn btn-primary text-white btn-md" title="Редагувати">
                                                            <i class="fas fa-edit "></i>
                                                        </a>
                                                    @endcan
                                                    &nbsp;
                                                    @csrf
                                                    @method('DELETE')

                                                    @can('admins_delete')
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
                            @endforeach
                        </tbody>
                    </table>

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
    <script>
        $(document).ready(() => {
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
