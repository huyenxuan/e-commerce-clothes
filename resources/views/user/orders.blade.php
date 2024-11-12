@extends('layouts.app')
@section('title')
    Đơn hàng của tôi
@endsection
@push('style')
    <style>
        .table> :not(caption)>tr>th {
            padding: 0.625rem 1.5rem .625rem !important;
            background-color: #6a6e51 !important;
        }

        .table>tr>td {
            padding: 0.625rem 1.5rem .625rem !important;
        }

        .table-bordered> :not(caption)>tr>th,
        .table-bordered> :not(caption)>tr>td {
            border-width: 1px 1px;
            border-color: #6a6e51;
        }

        .table> :not(caption)>tr>td {
            padding: .8rem 1rem !important;
        }

        .bg-success {
            background-color: #40c710 !important;
        }

        .bg-danger {
            background-color: #f44032 !important;
        }

        .bg-warning {
            background-color: #f5d700 !important;
            color: #000;
        }
    </style>
@endpush
@section('content')
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Danh sách đơn hàng</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-10">
                    <div class="wg-table table-all-user">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 80px">#</th>
                                        <th>Họ tên</th>
                                        <th class="text-center">Điện thoại</th>
                                        <th class="text-center">Giá trị</th>
                                        <th class="text-center">Thanh toán</th>

                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center">Ngày đặt</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">Ngày vận chuyển</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $index => $order)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td class="text-center">{{ $order->name }}</td>
                                            <td class="text-center">{{ $order->phone }}</td>
                                            <td class="text-center">${{ $order->subtotal }}</td>
                                            <td class="text-center">${{ $order->after_discount }}</td>

                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $order->status }}</span>
                                            </td>
                                            <td class="text-center">{{ $order->created_at }}</td>
                                            <td class="text-center">{{ $order->orderItems->count() }}</td>
                                            <td>{{ $order->delivered_date }}</td>
                                            <td class="text-center">
                                                <a href="account-orders-details.html">
                                                    <div class="list-icon-function view-icon">
                                                        <div class="item eye">
                                                            <i class="fa fa-eye"></i>
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
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection