@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">{{ __('Admin Control Panel') }}</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('orders.stats') }}">{{ __('Orders statistics') }}</a></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">

                    <form id="filter_form" action="" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label>{{ __('Category') }}</label>
                                <select name="categories[]" multiple="multiple"
                                    class="select2 form-control custom-select m-t-15">
                                    <option value="">{{ __('Any') }}</option>
                                    {!! \Owlwebdev\Ecom\Models\Category::getOptionsHTML(request()->input('categories')) !!}
                                </select>
                            </div>

                            <div class="form-group col-md-1">
                                <label>{{ __('Statuses') }}</label>
                                <select id="sizesSelect" name="status_id" class="form-control">
                                    <option value="">{{ __('Any') }}</option>
                                    @foreach ($statuses as $key => $status)
                                        @if (!in_array($key, [1, 7]))
                                            <option value="{{ $key }}"
                                                @if (old('status_id', request()->input('status_id')) == $key) selected @endif>{{ __($status) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Availability') }}</label>
                                <select name="quantity" class="select2 form-control custom-select m-t-15">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="1"@if (old('quantity', request()->input('quantity')) == '1') selected @endif>
                                        {{ __('Quantiy +') }}</option>
                                    <option value="0"@if (old('quantity', request()->input('quantity')) == '0') selected @endif>
                                        {{ __('Quantiy -') }}</option>
                                    <option value="10"@if (old('quantity', request()->input('quantity')) == '10') selected @endif>
                                        {{ __('Only :q left', ['q' => 10]) }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Status') }}</label>
                                <select name="status" class="select2 form-control custom-select m-t-15">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="1"@if (old('status', request()->input('status')) == '1') selected @endif>
                                        {{ __('Active entries') }}</option>
                                    <option value="0"@if (old('status', request()->input('status')) === '0') selected @endif>
                                        {{ __('Inactive entries') }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>{{ __('Period') }}</label>
                                <input type="text" name="period" class="form-control" style="width: 200px;">
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-success form-control">{{ __('Filter') }}</button>
                            </div>

                            <div class="form-group col-md-1">
                                <label>&nbsp;</label>
                                <a href="{{ route('orders.stats') }}"
                                    class="btn btn-danger form-control">{{ __('Clear') }}</a>
                            </div>
                        </div>
                    </form>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center font-weight-bold">{{ __('Name') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Option') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Category') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Orders count') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Quantity') }}</th>
                                <th class="text-center font-weight-bold">{{ __('total') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Total cost') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Margin') }}</th>
                                <th class="text-center font-weight-bold">{{ __('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($models as $model)
                                @foreach ($model->orderedInfo as $ordered)
                                    <tr data-id="{{ $model->id }}">
                                        <td class="text-left">
                                            @can('products_edit')
                                                <a href="{{ route('products.edit', $model) }}">
                                                    {{ $model->name }}
                                                </a>
                                            @else
                                                {{ $model->name }}
                                            @endcan
                                        </td>
                                        <td class="text-left">
                                            {{ $ordered->option_id ? $model->prices->where('id', $ordered->option_id)->first()->name ?? __('Deleted id:') . $ordered->option_id : '-' }}
                                        </td>
                                        <td class="text-left">{{ $model->categoriesToString() }}</td>

                                        <td class="text-left">{{ $ordered->aggregate }}</td>
                                        <td class="text-left">{{ $ordered->sum_count }}</td>
                                        <td class="text-left">{{ floatval($ordered->sum_price) }}</td>
                                        <td class="text-left">{{ floatval($ordered->sum_cost) }}</td>
                                        <td class="text-left">
                                            {{ floatval(round((($ordered->sum_price - $ordered->sum_cost) / ($ordered->sum_price == 0 ? 1 : $ordered->sum_price)) * 100, 2)) }}%
                                        </td>
                                        <td class="text-center">
                                            @can('products_edit')
                                                <a href="{{ route('products.edit', $model) }}" target="_blank"
                                                    class="btn btn-info  btn-md" title="{{ __('Product') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td class="text-center" colspan="9">{{ __('No data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-5 row">
                        <div class="col-sm-6 col-md-10">
                            {{ $models->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #5897fb !important;
            border: 1px solid #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }

        .select2-container--classic .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            background-color: #5897fb !important;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid #e9ecef;
            color: #3e5569;
            background-color: #fff;
            border-color: rgba(0, 0, 0, 0.25);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
        }

        .select2-container--default .select2-selection--multiple {
            height: inherit;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush


@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('input[name="period"]').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD',
                    customRangeLabel: "Довільний діапазон",
                    applyLabel: "Застосувати",
                    cancelLabel: "Відміна",
                    daysOfWeek: [
                        "Нд",
                        "Пн",
                        "Вт",
                        "Ср",
                        "Чт",
                        "Пт",
                        "Сб"
                    ],
                    monthNames: [
                        "Січень",
                        "Лютий",
                        "Березень",
                        "Квітень",
                        "Травень",
                        "Червень",
                        "Липень",
                        "Серпень",
                        "Вересень",
                        "Жовтень",
                        "Листопад",
                        "Грудень"
                    ],
                    firstDay: 1
                },
                startDate: "{{ $dateFrom }}",
                endDate: "{{ $dateTo }}",
                alwaysShowCalendars: true,
                ranges: {
                    'Сьогодні': [moment(), moment()],
                    'Вчора': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Останні 7 днів': [moment().subtract(6, 'days'), moment()],
                    'Останні 30 днів': [moment().subtract(29, 'days'), moment()],
                    'Цей місяць': [moment().startOf('month'), moment().endOf('month')],
                    'Минулий місяць': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
        });
    </script>
@endpush
