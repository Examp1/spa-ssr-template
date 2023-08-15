@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Permissions') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('permissions_create')
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary float-right">
                            <i class="fa fa-plus"></i>
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <form action="" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label>{{ __('Group') }}</label>
                                <select name="group" class="select2 form-control m-t-15">
                                    <option value="">---</option>
                                    @foreach (\App\Models\PermissionGroups::query()->pluck('name', 'id') as $key => $item)
                                        <option value="{{ $key }}"
                                            @if (old('group', request()->input('group')) == (string) $key) selected @endif>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-1">
                                <label for="inputPassword4">&nbsp;</label>
                                <button type="submit"
                                    class="btn btn-success form-control text-white">{{ __('Filter') }}</button>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="inputPassword4">&nbsp;</label>
                                <a href="{{ route('permissions.index') }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Group') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($model as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @can('permissions_edit')
                                            <a href="{{ route('permissions.edit', $item->id) }}">
                                                {{ $item->name }}
                                            </a>
                                        @else
                                            {{ $item->name }}
                                        @endcan
                                    </td>
                                    <td>{{ $item->groupName ?? '-' }}</td>
                                    <td>
                                        <div style="display: flex">
                                            <div style="margin-left: 10px">
                                                <form action="{{ route('permissions.destroy', $item->id) }}"
                                                    method="POST">
                                                    @can('permissions_edit')
                                                        <a href="{{ route('permissions.edit', $item->id) }}"
                                                            class="btn btn-md btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')

                                                    @can('permissions_delete')
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
                </div>
            </div>
        </div>
    </div>
@endsection

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
