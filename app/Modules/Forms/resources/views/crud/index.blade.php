@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Forms') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('forms_create')
                        <form action="{{ route(config('forms.route_name_prefix', 'admin.') . 'forms.store') }}" method="post"
                            style="display: flex; align-items: flex-end; gap: 15px">
                            @csrf

                            <input type="hidden" name="lang" value="{{ request('lang', config('translatable.locale')) }}">

                            <div>
                                <button type="submit" class="btn btn-sm btn-info" style="height: 35px;">
                                    <i class="far fa-plus-square"></i>
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </form>
                    @endcan
                </div>
                <div class="card-body">
                    <form action="" method="get" id="filter_form">
                        <div class="filter-container">
                            <ul class="nav nav-pills" style="display: inline-flex">
                                @foreach (config('translatable.locale_codes') as $langCode => $item)
                                    <li class="nav-item">
                                        <a style="padding: 7px 10px;"
                                            class="nav-link @if (request()->get('lang') === $langCode ||
                                                (!request()->get('lang') && config('translatable.locale') === $langCode)) active @endif"
                                            aria-current="page" href="?lang={{ $langCode }}">{{ $item }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div>
                                <div class="input-group">
                                    <input type="text" class="form-control input-search-widget"
                                        placeholder="{{ __('Search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-refresh-search"
                                            style="padding: 5px 12px;" type="button" title="Сбросить">
                                            <i class="mdi mdi-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <a href="/admin/forms?lang={{ request()->input('lang', config('translatable.locale')) }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                    </form>

                    @if ($forms->isNotEmpty())
                        <table class="table table-bordered imSortingTableLib">
                            <thead>
                                <tr>
                                    <th style="width: 50px">
                                        <input type="checkbox" class="check-all">
                                    </th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Group telegram') }}</th>
                                    <th>{{ __('Languages') }}</th>
                                    <th>Зв'язки</th>
                                    <th style="width: 210px">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($forms as $form)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox-item" data-check="{{ $form->id }}"
                                                name="check[]" value="{{ $form->id }}">
                                        </td>
                                        <td>
                                            @can('forms_edit')
                                                <a href="{{ route(config('forms.route_name_prefix', 'admin.') . 'forms.edit', ['form' => $form, config('forms.request_lang_key') => $form->lang]) }}"
                                                    title="{{ __('Edit') }}">
                                                    {{ $form->name }}
                                                </a>
                                            @else
                                                {{ $form->name }}
                                            @endcan
                                        </td>

                                        <td>{{ $groups[$form->group_id]['name'] ?? '-' }}</td>

                                        <td>{!! $form->showAllLanguagesNotEmpty() !!}</td>

                                        <td>
                                            <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                                                data-target="#exampleModal"
                                                data-whatever="{{ json_encode($form->getRelModels()) }}"><i
                                                    class="fa fa-info"></i></button>
                                        </td>

                                        <td class="text-center">
                                            <form
                                                action="{{ route(config('forms.route_name_prefix', 'admin.') . 'forms.destroy', $form) }}"
                                                method="post" class="delete-form-form">
                                                @csrf

                                                @method('delete')

                                                @can('forms_edit')
                                                    <a href="{{ route(config('forms.route_name_prefix', 'admin.') . 'forms.edit', ['form' => $form, config('forms.request_lang_key') => $form->lang]) }}"
                                                        class="btn btn-md btn-success text-white" title="{{ __('Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                <a href="/admin/forms/copy2/{{ $form->id }}"
                                                    class="btn btn-dark btn-md" title="{{ __('Copy') }}"><i
                                                        class="fa fa-copy"></i></a>

                                                @can('forms_delete')
                                                    <button type="submit" class="btn btn-md btn-danger delete-item text-white"
                                                        title="{{ __('Remove') }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <form action="/admin/forms/delete-selected" method="post" id="delete_sel_form">
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
                    @else
                        <h4>Нет форм</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Связанные модели</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="preview-widget">
        <img src="" alt="">
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-selection.select2-selection--single {
            height: 35px;
        }

        .select2-selection__arrow {
            height: 33px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 33px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        function inArray(needle, haystack) {
            var length = haystack.length;
            for (var i = 0; i < length; i++) {
                if (haystack[i] == needle) return true;
            }
            return false;
        }


        $(document).ready(function() {
            $('.btn-refresh-search').on('click', function() {
                $(".input-search-widget").val('');
                $(".input-search-widget").trigger('keyup');
            });



            $(".input-search-widget").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".imSortingTableLib tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $('.delete-form-form').submit(function(e) {
                e.preventDefault();
                if (confirm('Вы точно хотите удалить форму?')) {
                    e.target.submit();
                }
            });


            $('#exampleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var data = button.data('whatever');
                var modal = $(this)

                modal.find('.modal-body').html('');

                for (let i in data) {
                    modal.find('.modal-body').html(modal.find('.modal-body').html() + "<h4>" + i +
                        ":</h4>");
                    for (let j in data[i]) {
                        modal.find('.modal-body').html(modal.find('.modal-body').html() +
                            "<a target='_blank' href='" + data[i][j]['link'] + "'>" + data[i][j][
                                'title'
                            ] + "</a><br>");
                    }
                }
            });
        });
    </script>
@endpush
