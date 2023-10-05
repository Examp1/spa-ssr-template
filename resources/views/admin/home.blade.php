@extends('layouts.admin.app')

@section('content')
    <nav aria-label="breadcrumb" class="topInfo">
        <h3>{{ __('Dashboard') }}</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
        </ol>
    </nav>

    <div style="display: flex;align-items: baseline;">
        <h4>За період</h4>
        <form class="form-horizontal" method="get" style="display: inline-block;" id="form_p">
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" name="p" class="form-control" style="width: 200px;">
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-hover">
                <div class="box bg-cyan text-center">
                    <a href="/admin/orders?status_id=2">
                        <h1 class="font-light text-white"><i class="fa fa-cart-plus"></i></h1>
                        <h6 class="text-white">Нові замовлення {{$orders_by_status_data['new']['count']}}
                            ({{$orders_by_status_data['new']['price']}} грн)
                        </h6>
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-hover">
                <div class="box bg-warning text-center">
                    <a href="/admin/orders?status_id=5">
                        <h1 class="font-light text-white"><i class="mdi mdi-cart"></i></h1>
                        <h6 class="text-white">Комплектуються {{$orders_by_status_data['in_progress']['count']}}
                            ({{$orders_by_status_data['in_progress']['price']}} грн)
                        </h6>
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-hover">
                <div class="box bg-success text-center">
                    <a href="/admin/orders?status_id=6">
                        <h1 class="font-light text-white"><i class="mdi mdi-cart"></i></h1>
                        <h6 class="text-white">Виконані {{$orders_by_status_data['complete']['count']}}
                            ({{$orders_by_status_data['complete']['price']}} грн)
                        </h6>
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-3 col-xlg-3">
            <div class="card card-hover">
                <div class="box bg-danger text-center">
                    <a href="/admin/orders?status_id=3">
                        <h1 class="font-light text-white"><i class="mdi mdi-cart"></i></h1>
                        <h6 class="text-white">Скасовані {{$orders_by_status_data['canceled']['count']}}
                            ({{$orders_by_status_data['canceled']['price']}} грн)
                        </h6>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Sales chart -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- column -->
                        <div class="col-lg-12">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart" style="min-height: 300px"></div>
                            </div>
                        </div>
                        <!-- column -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center font-weight-bold">{{ __('Status') }}/{{ __('ID') }}</th>
                            <th class="text-center font-weight-bold">{{ __('User') }}</th>
                            <th class="text-center font-weight-bold">{{ __('Phone') }}</th>
                            <th class="text-center font-weight-bold">{{ __('Created') }}</th>
                            <th class="text-center font-weight-bold">{{ __('Price') }}</th>
                            <th class="text-center font-weight-bold">{{ __('Actions') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="text-center">
                                    @switch($order->order_status_id)
                                        @case(1)
                                            @if ($order->notified)
                                                <span class="badge badge-success font-weight-bold">{{ __('Notified') }}</span>
                                            @else
                                                <span class="badge badge-dark font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                            @endif
                                        @break

                                        @case(2)
                                            <span class="badge badge-warning font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                        @break

                                        @case(3)
                                        @case(8)
                                            <span class="badge badge-danger font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                        @break

                                        @case(4)
                                            <span class="badge badge-info font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                        @break

                                        @case(5)
                                            <span class="badge badge-secondary font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                        @break

                                        @case(6)
                                            <span class="badge badge-success font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                        @break

                                        @case(7)
                                            <span class="badge badge-danger font-weight-bold">{{ __($order->getOrderStatusAttribute()) }}</span>
                                        @break

                                        @default
                                    @endswitch
                                    <br>
                                    {{ $order->id }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.edit', $order) }}">
                                        {{ $order->user->name }}
                                    </a>
                                </td>
                                <td class="text-center">{{ $order->user->phone }}</td>
                                <td class="text-center">{{ $order->created_at->format('d-m-Y H:i') }}</td>
                                <td class="text-center">{{ $order->total ?? 0 }}</td>

                                <td class="text-center">
                                    <form action="{{ route('orders.trash', $order) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <a class="btn btn-success btn-sm" href="{{ route('orders.edit', $order) }}"><i class="far fa-edit"></i></a>
                                        <button type="submit" class="btn btn-danger btn-sm delete-item ml-1 delete-item"><i class="far fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">{{ __('No data') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-5 row">
                        <div class="col-sm-6 col-md-2">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries
                        </div>
                        <div class="col-sm-6 col-md-10">
                            {{ $orders->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush


@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $('input[name="p"]').daterangepicker({
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
                startDate: "{{$dateFrom}}",
                endDate: "{{$dateTo}}",
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

            $('input[name="p"]').on('apply.daterangepicker', function(ev, picker) {
                $("#form_p").submit();
            });

            plot1();

            function plot1() {
                var json_arr = {!! $days !!};

                var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: true,
                            fillColor: "#27a9e3",
                        }
                        , points: {
                            show: true
                        }
                    }
                    , grid: {
                        hoverable: true //IMPORTANT! this is needed for tooltip to work
                    }
                    , yaxis: {
                        min: 0
                        , minTickSize: 1,
                        showTickLabels:"major",
                    }
                    , xaxis: {
                        //ticks:null,
                        //showMinorTicks:null,
                        mode: "time",// null or "time"
                        minTickSize: [1, "day"],
                        min: {{ $min }},
                        max: {{ $max }},
                        twelveHourClock: true


                    }
                    , colors: ["#27a9e3", "#4fb9f0"]
                    , grid: {
                        color: "#AFAFAF"
                        , hoverable: true
                        , borderWidth: 0
                        , backgroundColor: '#FFF'
                    }
                    , tooltip: true
                    , tooltipOpts: {
                        content: "%x"
                        , shifts: {
                            //x: -60
                            //, y: 25
                        }
                    }
                };

                var plotObj = $.plot($("#flot-line-chart"), [{
                    data: {!! $days !!}
                    , label: "Замовлення"
                    , }, ], options);
            }
        });
    </script>
@endpush
