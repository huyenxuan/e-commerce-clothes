@extends('layouts.admin')
@section('title')
    Chi tiết đơn hàng
@endsection
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chi tiết đơn hàng</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Chi tiết đơn hàng</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Chi tiết đơn hàng</h5>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">Quay lại</a>
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
                                <th class="text-center">Lựa chọn</th>
                                <th class="text-center">Trạng thái trả lại</th>
                                <th class="text-center">Hành động</th>
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
                                                target="_blank" class="body-title-2">{{ $orderItem->product->name }}</a>
                                        </div>
                                    </td>
                                    <td class="text-center">${{ $orderItem->price }}</td>
                                    <td class="text-center">{{ $orderItem->quantity }}</td>
                                    <td class="text-center">{{ $orderItem->product->SKU }}</td>
                                    <td class="text-center">{{ $orderItem->product->category->name }}</td>
                                    <td class="text-center">{{ $orderItem->product->brand->name }}</td>
                                    <td class="text-center">{{ $orderItem->options }}</td>
                                    <td class="text-center">{{ $orderItem->rstatus == 0 ? 'Không' : 'Có' }}</td>
                                    <td class="text-center">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="icon-eye"></i>
                                            </div>
                                        </div>
                                    </td>
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
                <h5>Địa chỉ: </h5>
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
                <h5>Giao dịch</h5>
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
                            <th>Hình thức thanh toán</th>
                            <td>COD</td>
                            <th>Tình trạng đơn hàng</th>
                            <td>
                                @if ($order->status == 'Đã đặt hàng')
                                    <span class="badge bg-warning">Đã đặt hàng</span>
                                @elseif ($order->status == 'Đã vận chuyển')
                                    <span class="badge bg-success">Đã vận chuyển</span>
                                @else
                                    <span class="badge bg-danger">Đã hủy</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày đặt</th>
                            <td>{{ $order->created_at }}</td>
                            <th>Ngày giao hàng</th>
                            <td>{{ $order->delivered_date }}</td>
                            <th>Ngày hủy hàng</th>
                            <td>{{ $order->canceled_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="wg-box mt-5">
                <h5>Cập nhật trạng thái đơn hàng</h5>
                <form action="{{ route('admin.order.update_status') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="select">
                                <select name="status" id="order_status">
                                    <option {{ $order->status == 'Đã đặt hàng' ? 'selected' : '' }} value="Đã đặt hàng">Đã
                                        đặt
                                        hàng</option>
                                    <option {{ $order->status == 'Đã vận chuyển' ? 'selected' : '' }}
                                        value="Đã vận chuyển">Đã
                                        vận chuyển</option>
                                    <option {{ $order->status == 'Đã hủy' ? 'selected' : '' }} value="Đã hủy">Đã hủy
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary tf-button w-208">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
