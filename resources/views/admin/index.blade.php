@extends('layouts.admin')
@section('title')
    Trang admin
@endsection
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="tf-section-2 mb-30">
                <div class="flex gap20 flex-wrap-mobile">
                    <div class="w-half">
                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Tổng số đơn đặt hàng</div>
                                        <h4>{{ $dashboardDatas[0]->total }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Tổng số tiền</div>
                                        <h4>{{ $dashboardDatas[0]->totalAmount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Đơn hàng chờ xử lý</div>
                                        <h4>{{ $dashboardDatas[0]->totalOrdered }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Số tiền đơn hàng đang chờ xử lý</div>
                                        <h4>{{ $dashboardDatas[0]->totalOrderedAmount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-half">
                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Đơn hàng đã giao</div>
                                        <h4>{{ $dashboardDatas[0]->totalDelivered }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Số tiền đơn hàng đã giao</div>
                                        <h4>{{ $dashboardDatas[0]->totalDeliveredAmount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default mb-20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Số đơn hàng đã hủy</div>
                                        <h4>{{ $dashboardDatas[0]->totalCanceled }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wg-chart-default">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap14">
                                    <div class="image ic-bg">
                                        <i class="icon-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="body-text mb-2">Số tiền đơn hàng bị hủy</div>
                                        <h4>{{ $dashboardDatas[0]->totalCanceledAmount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wg-box">
                    <div class="flex items-center justify-between">
                        <h5>Doanh thu tháng</h5>
                    </div>
                    <div class="flex flex-wrap gap40">
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t1"></div>
                                    <div class="text-tiny">Doanh thu</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>${{ $totalAmount }}</h4>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t2"></div>
                                    <div class="text-tiny">Chưa xử lý</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>${{ $totalOrderedAmount }}</h4>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t4"></div>
                                    <div class="text-tiny">Đã vận chuyển</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>${{ $totalDeliveredAmount }}</h4>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <div class="block-legend">
                                    <div class="dot t3"></div>
                                    <div class="text-tiny">Đã hủy</div>
                                </div>
                            </div>
                            <div class="flex items-center gap10">
                                <h4>${{ $totalCanceledAmount }}</h4>
                            </div>
                        </div>
                    </div>
                    <div id="line-chart-8"></div>
                </div>
            </div>
            <div class="tf-section mb-30">

                <div class="wg-box">
                    <div class="flex items-center justify-between">
                        <h5>Đơn đặt hàng gần đây</h5>
                        <div class="dropdown default">
                            <a class="btn btn-secondary dropdown-toggle" href="{{ route('admin.orders') }}">
                                <span class="view-all">Xem tất cả</span>
                            </a>
                        </div>
                    </div>
                    <div class="wg-table table-all-user">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:70px">#</th>
                                        <th class="text-center">Họ tên</th>
                                        <th class="text-center">Số điện thoại</th>
                                        <th class="text-center">Giá đơn hàng</th>
                                        <th class="text-center">Thực thu</th>

                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Ngày đặt</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">Giao hàng</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $index => $order)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{ $order->user->name }}</td>
                                            <td class="text-center">{{ $order->phone }}</td>
                                            <td class="text-center">${{ $order->subtotal }}</td>
                                            <td class="text-center">${{ $order->after_discount ?? 0 }}</td>

                                            <td class="text-center">
                                                @if ($order->status == 'Đã đặt hàng')
                                                    <span class="badge bg-warning">Đã đặt hàng</span>
                                                @elseif ($order->status == 'Đã vận chuyển')
                                                    <span class="badge bg-success">Đã vận chuyển</span>
                                                @else
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $order->created_at }}</td>
                                            <td class="text-center">{{ $order->orderItems->count() }}</td>
                                            <td class="text-center">{{ $order->delivered_date }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.order.details', ['id' => $order->id]) }}">
                                                    <div class="list-icon-function view-icon">
                                                        <div class="item eye">
                                                            <i class="icon-eye"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        (function($) {

            var tfLineChart = (function() {

                var chartBar = function() {

                    var options = {
                        series: [{
                                name: 'Tổng',
                                data: [{{ $amountM }}]
                            }, {
                                name: 'Chờ xử lý',
                                data: [{{ $OrderedAmountM }}]
                            },
                            {
                                name: 'Đã vận chuyển',
                                data: [{{ $DeliveredAmountM }}]
                            }, {
                                name: 'Đã hủy',
                                data: [{{ $CanceledAmountM }}]
                            }
                        ],
                        chart: {
                            type: 'bar',
                            height: 325,
                            toolbar: {
                                show: false,
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '10px',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        legend: {
                            show: false,
                        },
                        colors: ['#2377FC', '#FFA500', '#078407', '#FF0000'],
                        stroke: {
                            show: false,
                        },
                        xaxis: {
                            labels: {
                                style: {
                                    colors: '#212529',
                                },
                            },
                            categories: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5',
                                'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11',
                                'Tháng 12'
                            ],
                        },
                        yaxis: {
                            show: false,
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return "$ " + val + ""
                                }
                            }
                        }
                    };

                    chart = new ApexCharts(
                        document.querySelector("#line-chart-8"),
                        options
                    );
                    if ($("#line-chart-8").length > 0) {
                        chart.render();
                    }
                };

                /* Function ============ */
                return {
                    init: function() {},

                    load: function() {
                        chartBar();
                    },
                    resize: function() {},
                };
            })();
            jQuery(document).ready(function() {});
            jQuery(window).on("load", function() {
                tfLineChart.load();
            });
            jQuery(window).on("resize", function() {});
        })(jQuery);
    </script>
@endpush
