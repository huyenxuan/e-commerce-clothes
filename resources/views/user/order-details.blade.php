@extends('layouts.app')
@section('title')
    Chi tiết đơn hàng
@endsection
@section('content')
    @push('style')
        <style>
            .pt-90 {
                padding-top: 90px !important;
            }

            .pr-6px {
                padding-right: 6px;
                text-transform: uppercase;
            }

            .my-account .page-title {
                font-size: 1.5rem;
                font-weight: 700;
                text-transform: uppercase;
                margin-bottom: 40px;
                border-bottom: 1px solid;
                padding-bottom: 13px;
            }

            .my-account .wg-box {
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                padding: 24px;
                flex-direction: column;
                gap: 24px;
                border-radius: 12px;
                background: var(--White);
                box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
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

            .table-transaction>tbody>tr:nth-of-type(odd) {
                --bs-table-accent-bg: #fff !important;

            }

            .table-transaction th,
            .table-transaction td {
                padding: 0.625rem 1.5rem .25rem !important;
                color: #000 !important;
            }

            .table> :not(caption)>tr>th {
                padding: 0.625rem 1.5rem .25rem !important;
                background-color: #6a6e51 !important;
            }

            .table-bordered>:not(caption)>*>* {
                border-width: inherit;
                line-height: 32px;
                font-size: 14px;
                border: 1px solid #e1e1e1;
                vertical-align: middle;
            }

            .table-striped .image {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 50px;
                height: 50px;
                flex-shrink: 0;
                border-radius: 10px;
                overflow: hidden;
            }

            .table-striped td:nth-child(1) {
                min-width: 250px;
                padding-bottom: 7px;
            }

            .pname {
                display: flex;
                gap: 13px;
            }

            .table-bordered> :not(caption)>tr>th,
            .table-bordered> :not(caption)>tr>td {
                border-width: 1px 1px;
                border-color: #6a6e51;
            }
        </style>
    @endpush
    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Chi tiết đơn hàng</h2>
            <div class="row">
                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">
                    <div class="wg-box">
                        <div class="flex items-center justify-between gap10 flex-wrap">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="fw-bold">Chi tiết đơn hàng</h5>
                                </div>
                                <div class="col-6 text-right">
                                    <a class="btn btn-sm btn-danger fw-bold" href="{{ route('user.orders') }}">Quay lại</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center w-25">Tên sản phẩm</th>
                                        <th class="text-center">Đơn giá</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">Mã mặt hàng</th>
                                        <th class="text-center">Danh mục</th>
                                        <th class="text-center">Thương hiệu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $index => $orderItem)
                                        <tr>
                                            <td class="pname">
                                                <div class="image">
                                                    <img src="{{ asset('uploads/products/' . $orderItem->product->image) }}"
                                                        alt="{{ $orderItem->product->name }}" class="image">
                                                </div>
                                                <div class="name">
                                                    <a href="{{ route('shop.product.details', ['product_slug' => $orderItem->product->slug]) }}"
                                                        target="_blank"
                                                        class="body-title-2">{{ $orderItem->product->name }}</a>
                                                </div>
                                            </td>
                                            <td class="text-center">${{ $orderItem->price }}</td>
                                            <td class="text-center">{{ $orderItem->quantity }}</td>
                                            <td class="text-center">{{ $orderItem->product->SKU }}</td>
                                            <td class="text-center">{{ $orderItem->product->category->name }}</td>
                                            <td class="text-center">{{ $orderItem->product->brand->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divider"></div>
                        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                            {{ $orderItems->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                    <div class="wg-box mt-5">
                        <h5 class="fw-bold">Địa chỉ: </h5>
                        <div class="my-account__address-item col-md-6">
                            <div class="my-account__address-item__detail">
                                <p>Họ tên: {{ $order->name }}</p>
                                <p>Đất nước: {{ $order->country }}</p>
                                <p>Tỉnh/Thành phố: {{ $order->city }}</p>
                                <p>Huyện/Quận: {{ $order->state }}</p>
                                <p>Xã: {{ $order->locality }}</p>
                                <p>Địa chỉ: {{ $order->address }}</p>
                                <p>Số điện thoại : {{ $order->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="wg-box mt-5">
                        <h5 class="fw-bold">Giao dịch</h5>
                        <table class="table table-striped table-bordered table-transaction">
                            <tbody>
                                <tr>
                                    <th>Giá trị đơn hàng</th>
                                    <td>{{ $order->subtotal }}</td>
                                    <th>Khuyến mại</th>
                                    <td>${{ $order->subtotal - $order->after_discount }}</td>
                                    <th>Thực thu</th>
                                    <td>${{ $order->after_discount }}</td>
                                </tr>
                                <tr>
                                    <th>Thanh toán</th>
                                    <td>COD</td>
                                    <th>Tình trạng</th>
                                    <td>
                                        @if ($order->status == 'Đã đặt hàng')
                                            <span class="badge bg-warning">Đã đặt hàng</span>
                                        @elseif ($order->status == 'Đã vận chuyển')
                                            <span class="badge bg-success">Đã vận chuyển</span>
                                        @else
                                            <span class="badge bg-danger">Đã hủy</span>
                                        @endif
                                    </td>
                                    <th>Ngày đặt</th>
                                    <td>{{ $order->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày vận chuyển</th>
                                    <td>{{ $order->delivered_date }}</td>
                                    <th>Ngày hủy hàng</th>
                                    <td>{{ $order->canceled_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if ($order->status != 'Đã hủy' && $order->status != 'Đã vận chuyển')
                        <div class="wg-box mt-5">
                            <form action="{{ route('user.order.cancel_order') }}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <button class="btn btn-danger cancel-order">Hủy đơn</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.cancel-order').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Bạn muốn xóa?",
                    text: "Bạn chắc chắn muốn hủy đơn?",
                    icon: "warning",
                    buttons: ["Không", "Có"],
                    dangerMode: true,
                }).then(function(result) {
                    if (result) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
