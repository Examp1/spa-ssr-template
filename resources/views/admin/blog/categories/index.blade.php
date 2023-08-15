@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item">{{ __('Blog') }}</li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Categories') }}</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('setting_blogcategories_view')
                        <a href="/admin/settings/blogcategories" class="float-right btn btn-dark" style="margin-left: 10px"
                            title="{{ __('Settings') }}">
                            <i class="fa fa-cog"></i>
                        </a>
                    @endcan
                    @can('blog_category_create')
                        <a href="{{ route('blog.categories.create') }}" class="btn btn-success text-white float-right">
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
                                <a href="{{ route('blog.categories.index') }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="{{ old('status', request()->input('status')) }}">
                        <a href="javascript:void(0)" class="set-filter-status" data-status="1">{{ __('Active entries') }}
                            ({{ \App\Models\BlogCategories::query()->active()->count() }})</a>
                        <a href="javascript:void(0)" class="set-filter-status" data-status="0"
                            style="margin-left: 10px">{{ __('Inactive entries') }}
                            ({{ \App\Models\BlogCategories::query()->notActive()->count() }})</a>
                    </form>

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
                                <th class="sorting @if (request()->input('sort') == 'status' && request()->input('order') == 'asc') sorting_asc @elseif(request()->input('sort') == 'status' && request()->input('order') == 'desc') sorting_desc @endif"
                                    data-field="status">{{ __('Status') }}</th>
                                <th>{{ __('Languages') }}</th>
                                <th style="width: 60px">{{ __('Meta-tags') }}</th>
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
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @can('blog_category_edit')
                                            <a href="{{ route('blog.categories.edit', $item->id) }}">
                                                {{ $item->getNameWithPath() }}
                                            </a>
                                        @else
                                            {{ $item->getNameWithPath() }}
                                        @endcan
                                    </td>
                                    <td>{!! $item->showStatus() !!}</td>
                                    <td>{!! $item->showAllLanguagesNotEmpty() !!}</td>
                                    <td>{!! $item->showMetaInfo() !!}</td>
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        <div style="display: flex">
                                            <div style="margin-left: 10px">
                                                <form action="{{ route('blog.categories.destroy', $item->id) }}"
                                                    method="POST">

                                                    <a href="{{ $item->frontLink(true) }}" target="_blank"
                                                        class="btn btn-info btn-md" title="{{ __('Preview') }}"><i
                                                            class="fa fa-eye"></i></a>

                                                    @can('blog_category_edit')
                                                        <a href="{{ route('blog.categories.edit', $item->id) }}"
                                                            class="btn btn-md btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan

                                                    @csrf
                                                    @method('DELETE')

                                                    @can('blog_category_delete')
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

                    <form action="{{ route('blog.categories.delete-selected') }}" method="post" id="delete_sel_form">
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
