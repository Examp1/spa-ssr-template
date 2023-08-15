@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Widgets') }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @can('widgets_create')
                        <form action="{{ route(config('widgets.route_name_prefix', 'admin.') . 'widgets.store') }}"
                            method="post" style="display: flex; align-items: flex-end; gap: 15px">
                            @csrf

                            <input type="hidden" name="lang" value="{{ request('lang', config('translatable.locale')) }}">

                            <div>
                                <label class="mr-6">{{ __('Group') }} <span class="text-danger">*</span></label>

                                <div>
                                    <select id="widgetGroup" class="form-control">
                                        <option value="">---</option>
                                        @foreach (config('widgets.groups') as $key => $name)
                                            <option value="{{ $key }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="widgetInstance" class="mr-3">
                                    {{ __('Widget template') }}
                                    <span class="text-danger">*</span>
                                </label>

                                <div>
                                    <select id="widgetInstance" name="instance"
                                        class="form-control custom-select-sm @error('instance') is-invalid @enderror" disabled>
                                        <option value="">---</option>
                                        @foreach ($list as $instance => $name)
                                            <option value="{{ $instance }}"
                                                data-groups="{{ json_encode(\App\Modules\Widgets\Models\Widget::getGroupsIdsByInstance($instance)) }}"
                                                data-icon="{{ $listPreview[$instance] ?? '' }}"
                                                @if (old('instance') == $instance) selected @endif>{{ trans($name) }}</option>
                                        @endforeach
                                    </select>

                                    @error('instance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-md btn-success text-white" style="height: 35px;">
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
                                <input type="hidden" name="lang"
                                    value="{{ request()->input('lang', config('translatable.locale')) }}">
                                <select class="form-control select2-field-template" name="template">
                                    <option value="">{{ __('Sort by template') }}</option>
                                    @foreach ($list as $instance => $name)
                                        <option value="{{ $instance }}"
                                            @if (request()->input('template') == $instance) selected @endif>{{ trans($name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <select class="form-control select2-field-group" name="group">
                                    <option value="">{{ __('Sort by group') }}</option>
                                    @foreach (config('widgets.groups') as $key => $name)
                                        <option value="{{ $key }}"
                                            @if (request()->input('group') == $key) selected @endif>{{ trans($name) }}</option>
                                    @endforeach
                                </select>
                            </div>

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
                                <a href="/admin/widgets?lang={{ request()->input('lang', config('translatable.locale')) }}"
                                    class="btn btn-danger form-control text-white">{{ __('Clear') }}</a>
                            </div>
                        </div>
                    </form>

                    @if ($widgets->isNotEmpty())
                        <table class="table table-bordered imSortingTableLib">
                            <thead>
                                <tr>
                                    <th style="width: 50px">
                                        <input type="checkbox" class="check-all">
                                    </th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Languages') }}</th>
                                    <th>{{ __('Template') }}</th>
                                    <th>{{ __('Groups') }}</th>
                                    <th>{{ __('Connections') }}</th>
                                    <th style="width: 210px">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($widgets as $widget)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox-item" data-check="{{ $widget->id }}"
                                                name="check[]" value="{{ $widget->id }}">
                                        </td>
                                        <td>
                                            @can('widgets_edit')
                                                <a href="{{ route(config('widgets.route_name_prefix', 'admin.') . 'widgets.edit', ['widget' => $widget, config('widgets.request_lang_key') => $widget->lang]) }}"
                                                    title="{{ __('Edit') }}">
                                                    {{ $widget->name }}
                                                </a>
                                            @else
                                                {{ $widget->name }}
                                            @endcan
                                        </td>

                                        <td>{!! $widget->showAllLanguagesNotEmpty() !!}</td>

                                        <td>
                                            <span class="text-gray font-weight-normal">
                                                <a href="javascript:void(0)" data-template="{{ $widget->instance }}"
                                                    class="template-filter-link">
                                                    {{ isset($list[$widget->instance]) ? $list[$widget->instance] : $widget->instance }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            @foreach ($widget->getGroups() as $key => $group)
                                                <a href="javascript:void(0)" data-group="{{ $key }}"
                                                    class="group-filter-link">
                                                    <span class="badge badge-primary">{{ $group }}</span>
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-md" data-toggle="modal"
                                                data-target="#exampleModal"
                                                data-whatever="{{ json_encode($widget->getRelModels()) }}"><i
                                                    class="fa fa-info"></i></button>
                                        </td>

                                        <td class="text-center">
                                            <form
                                                action="{{ route(config('widgets.route_name_prefix', 'admin.') . 'widgets.destroy', $widget) }}"
                                                method="post" class="delete-widget-form">
                                                @csrf

                                                @method('delete')

                                                @can('widgets_edit')
                                                    <a href="{{ route(config('widgets.route_name_prefix', 'admin.') . 'widgets.edit', ['widget' => $widget, config('widgets.request_lang_key') => $widget->lang]) }}"
                                                        class="btn btn-md btn-success text-white"
                                                        title="{{ __('Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                <a href="/admin/widgets/copy2/{{ $widget->id }}"
                                                    class="btn btn-dark btn-md" title="{{ __('Copy') }}"><i
                                                        class="fa fa-copy"></i></a>

                                                @can('widgets_delete')
                                                    <button type="submit"
                                                        class="btn btn-md btn-danger delete-item text-white"
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

                        <form action="/admin/widgets/delete-selected" method="post" id="delete_sel_form">
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
                        <h4>{{ __('No widgets') }}</h4>
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
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Connected Models') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
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
        .img-flag {
            width: 50px;
            height: auto;
        }

        .preview-widget {
            width: 400px;
            height: 400px;
            display: none;
            align-items: center;
            justify-content: center;
            position: fixed;
            left: 100px;
            top: 100px;
            background-color: #fff;
            border: 1px #ccc solid;
            z-index: 9999;
        }

        .preview-widget img {
            width: 90%;
            height: auto;
        }

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

        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var baseUrl = "/images/widgets";
            if (state.element.dataset.icon) {
                var $state = $(
                    '<span><img src="' + baseUrl + '/' + state.element.dataset.icon + '" class="img-flag" /> ' + state
                    .text + '</span>'
                );
            } else {
                var $state = $(
                    '<span> ' + state.text + '</span>'
                );
            }
            return $state;
        };

        $(document).ready(function() {
            var optionsOrig = [];

            $("#widgetInstance option").each(function() {
                optionsOrig.push($(this)[0].outerHTML);
            });

            console.log(optionsOrig);

            $("#widgetInstance").select2({
                templateResult: formatState
            });

            $(document.body).on("change", "#widgetInstance", function() {
                $(".preview-widget").find('img').attr('src', '');
                $(".preview-widget").css('display', 'none');
            });

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

            $('.delete-widget-form').submit(function(e) {
                e.preventDefault();
                if (confirm('Вы точно хотите удалить виджет?')) {
                    e.target.submit();
                }
            });

            $(document).on('mouseover', '.img-flag', function(e) {
                let src = $(this).attr('src');

                console.log(e.pageX, e.pageY)

                $(".preview-widget").css('left', e.pageX + 30 + 'px');
                $(".preview-widget").css('top', e.pageY + 30 + 'px');

                $(".preview-widget").find('img').attr('src', src);
                $(".preview-widget").css('display', 'flex');
            });

            $(document).on('mouseleave', '.img-flag', function() {
                $(".preview-widget").find('img').attr('src', '');
                $(".preview-widget").css('display', 'none');
            });

            $('.select2-field-template, .select2-field-group').on('change', function(e) {
                $(this).closest('form').submit();
            });

            $(".template-filter-link").on('click', function() {
                let template = $(this).data('template');

                $(".select2-field-template").val(template).trigger('change');
                $("#filter_form").submit();
            });

            $(".group-filter-link").on('click', function() {
                let group = $(this).data('group');

                $(".select2-field-group").val(group).trigger('change');
                $("#filter_form").submit();
            });

            $('#exampleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var data = button.data('whatever');
                var modal = $(this)

                modal.find('.modal-body').html('');

                for (let i in data) {
                    modal.find('.modal-body').html(modal.find('.modal-body').html() + "<h4>" + i + ":</h4>");
                    for (let j in data[i]) {
                        modal.find('.modal-body').html(modal.find('.modal-body').html() +
                            "<a target='_blank' href='" + data[i][j]['link'] + "'>" + data[i][j]['title'] + "</a><br>");
                    }
                }
            });

            $("#widgetGroup").on('change', function() {
                let val = $(this).val();

                $("#widgetInstance").select2('destroy');

                $("#widgetInstance").html('<option value="">---</option>');

                // filter
                for (let i = 0; i < optionsOrig.length; i++) {
                    let opt = $(optionsOrig[i]);
                    let groups = opt.data('groups');

                    if (groups !== undefined) {
                        if (inArray(val, groups)) {
                            $("#widgetInstance").append(optionsOrig[i]);
                        }
                    }
                }

                $("#widgetInstance").select2({
                    templateResult: formatState
                });

                if (val) {
                    $("#widgetInstance").prop('disabled', false).trigger('change');
                } else {
                    $("#widgetInstance").prop('disabled', true).trigger('change');
                }
            })
        });
    </script>
@endpush

