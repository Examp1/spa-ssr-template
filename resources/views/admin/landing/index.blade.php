@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Landings') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('setting_landing_view')
                        <a href="/admin/settings/landing" class="float-right btn btn-dark" style="margin-left: 10px"
                            title="{{ __('Settings') }}">
                            <i class="fa fa-cog"></i>
                        </a>
                    @endcan
                    @can('landing_create')
                        <a href="{{ route('landing.create') }}" class="btn btn-success text-white float-right">
                            <i class="fa fa-plus"></i>
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <form action="" method="get">
                        <div class="filter-container">
                            <div>
                                <label>{{ __('Search') }}</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="title"
                                        value="{{ old('title', request()->input('title')) }}">
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
                                <a href="{{ route('landing.index') }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="{{ old('status', request()->input('status')) }}">
                        <a href="javascript:void(0)" class="set-filter-status" data-status="1">{{ __('Active entries') }}
                            ({{ \App\Models\Landing::query()->active()->count() }})</a>
                        <a href="javascript:void(0)" class="set-filter-status" data-status="0"
                            style="margin-left: 10px">{{ __('Inactive entries') }}
                            ({{ \App\Models\Landing::query()->notActive()->count() }})</a>
                    </form>

                    <table class="table table-bordered imSortingTableLib">
                        <thead>
                            <tr>
                                <th style="width: 50px">
                                    <input type="checkbox" class="check-all">
                                </th>
                                <th class="sorting @if (request()->input('sort') == 'id' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'id' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="id">{{ __('ID') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'title' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'title' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="title">Заголовок</th>
                                <th class="sorting @if (request()->input('sort') == 'status' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'status' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="status">{{ __('Status') }}</th>
                                <th>{{ __('Languages') }}</th>
                                <th style="width: 60px">{{ __('Meta-tags') }}</th>
                                <th class="sorting @if (request()->input('sort') == 'created_at' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'created_at' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="created_at">{{ __('Created at') }}</th>
                                <th style="width: 220px">{{ __('Actions') }}</th>
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
                                        @can('landing_edit')
                                            <a href="{{ route('landing.edit', $item->id) }}">
                                                {{ strip_tags($item->title) }}
                                            </a>
                                        @else
                                            {{ strip_tags($item->title) }}
                                        @endcan
                                    </td>
                                    <td>{!! $item->showStatus() !!}</td>
                                    <td>{!! $item->showAllLanguagesNotEmpty() !!}</td>
                                    <td>{!! $item->showMetaInfo() !!}</td>
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        <div style="display: flex">
                                            <div style="margin-left: 10px">
                                                <form action="{{ route('landing.destroy', $item->id) }}" method="POST">

                                                    <a href="{{ $item->frontLink(true) }}" target="_blank"
                                                        title="{{ __('Preview') }}" class="btn btn-info  btn-md"><i
                                                            class="fa fa-eye"></i></a>

                                                    @can('landing_edit')
                                                        <a href="{{ route('landing.edit', $item->id) }}"
                                                            class="btn btn-md btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    <a href="{{ route('landing.copy', ['id' => $item->id]) }}"
                                                        class="btn btn-dark btn-md" title="{{ __('Copy') }}"><i
                                                            class="fa fa-copy"></i></a>

                                                    @csrf
                                                    @method('DELETE')

                                                    @can('landing_delete')
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

                    <form action="{{ route('landing.delete-selected') }}" method="post" id="delete_sel_form">
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
