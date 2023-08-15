@extends('layouts.admin.app')

@section('content')
    @php($tags = \App\Models\Menu::getTags())
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Menu') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" id="admin_menu">
                    <div class="form-row">
                        <div class="col-md-6">
                            <form action="{{ route('menu.index') }}" method="get">
                                <div class="row">
                                    <div class="form-group col-md-4">{{ __('Select the menu to modify') }}:</div>
                                    <div class="form-group col-md-4">
                                        <select name="tag" class="form-control">
                                            @foreach ($tags as $key => $item)
                                                <option value="{{ $item }}" data-id="{{ $key }}"
                                                    @if ($item === $tag) selected @endif>
                                                    {{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="submit" value="Оберіть" class="btn btn-primary text-white">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            @can('menu_create')
                                <form action="{{ route('menu.add-menu') }}" method="post">
                                    @csrf
                                    <div class="row float-right">
                                        <div class="form-group col-4">{{ __('Create new') }}</div>
                                        <div class="form-group col-5">
                                            <input type="text" name="tag" placeholder="{{ __('Menu name') }}"
                                                class="form-control{{ $errors->has('tag') ? ' is-invalid' : '' }}">

                                            @if ($errors->has('tag'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('tag') }}</strong>
                                                </span>
                                            @endif

                                            <input type="hidden" name="const" value="1">
                                        </div>
                                        <div class="form-group col-3">
                                            <input type="submit" value="{{ __('Create') }}"
                                                class="btn btn-success text-white">
                                        </div>
                                    </div>
                                </form>
                            @endcan
                        </div>
                    </div>

                    @if ($tag)
                        <div class="row">
                            <div class="col-3">
                                <div class="card">
                                    <div class="card-header">{{ __('Add to menu') }}</div>
                                    <div class="card-body">
                                        <div id="accordion">
                                            @foreach (config('menu.entities') as $modelClass)
                                                {!! $modelClass::showCardMenu($tag) !!}
                                            @endforeach

                                            @include('admin.pieces.menu.card', [
                                                'typeName' => 'Link',
                                                'shownName' => __('Arbitrary links'),
                                                'tag' => $tag,
                                                'type' => \App\Models\Menu::TYPE_ARBITRARY,
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="card">
                                    <div class="card-header">{{ __('Selected menu') }} &laquo;{{ $tag }}&raquo;
                                        ID:{{ array_flip($tags)[$tag] }}</div>
                                    <div class="card-body">
                                        <TreeCategories :categories="{{ $model }}" :tag="{{ json_encode($tag) }}"
                                            :types="{{ json_encode(\App\Models\Menu::getTypes()) }}"></TreeCategories>

                                        @can('menu_delete')
                                            <form action="{{ route('menu.delete-menu') }}" method="post"
                                                id="delete_menu_form">
                                                @csrf
                                                <input type="hidden" name="tag" value="{{ $tag }}">
                                            </form>
                                            <span class="btn btn-danger text-white float-right delete-menu-btn">{{ __('Remove') }}</span>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <h3>{{ __('Select the menu') }}</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
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
    <script src="{{ asset('/js/admin_menu.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2-elem').each(function() {
                $(this).select2({
                    placeholder: "Оберіть із списку",
                    allowClear: true,
                }).on('change', function(e) {
                    $(this).closest('form').find('input[name="name"]').val($(this).find(':selected')
                        .text());
                });
            });

            $('.select2-elem').each(function() {
                $(this).val('-1').trigger('change');
            });

            $('.delete-menu-btn').on('click', function() {
                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: "{{ __('Are you trying to delete?') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('Yes, do it!') }}',
                    cancelButtonText: '{{ __('No') }}'
                }).then((result) => {
                    if (result.value) {
                        $("#delete_menu_form").submit();
                    }
                })
            })
        });
    </script>
@endpush
