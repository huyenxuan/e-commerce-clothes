@extends('layouts.app')
@section('title')
    Thanh toán
@endsection
@section('content')
    <style>
        .option-detail,
        .policy-text {
            text-align: justify;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Vận chuyển và thanh toán</h2>
            <div class="checkout-steps">
                <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Giỏ hàng</span>
                        <em>Quản lý danh sách các mặt hàng của bạn</em>
                    </span>
                </a>
                <a href="{{ route('cart.checkout') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Vận chuyển và thanh toán</span>
                        <em>Kiểm tra danh sách các mục của bạn</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Xác nhận</span>
                        <em>Xem xét và gửi đơn đặt hàng của bạn</em>
                    </span>
                </a>
            </div>
            <form name="checkout-form" action="{{ route('cart.place_an_order') }}" method="POST">
                @csrf
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-uppercase">Chi tiết vận chuyển</h2>
                            </div>
                        </div>

                        @if ($address)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-account__address-list">
                                        <div class="my-account__address-list-item">
                                            <div class="my-account__address-item__detail">
                                                <p>{{ $address->name }}</p>
                                                <p>{{ $address->phone }}</p>
                                                <p>{{ $address->city }}</p> {{-- thành phố --}}
                                                <p>{{ $address->state }}</p> {{-- quận huyện --}}
                                                <p>{{ $address->locality }}</p> {{-- xã phường --}}
                                                <p>{{ $address->address }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="name" required=""
                                            value="{{ old('name') }}">
                                        <label for="name">Họ và tên *</label>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="phone" required=""
                                            value="{{ old('phone') }}">
                                        <label for="phone">Số điện thoại *</label>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="city" required=""
                                            value="{{ old('city') }}">
                                        <label for="city">Thành phố / Tỉnh *</label>
                                        @error('city')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mt-3 mb-3">
                                        <input type="text" class="form-control" name="state" required=""
                                            value="{{ old('state') }}">
                                        <label for="state">Quận / Huyện *</label>
                                        @error('state')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="locality" required=""
                                            value="{{ old('locality') }}">
                                        <label for="locality">Phường / Xã *</label>
                                        @error('locality')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="address" required=""
                                            value="{{ old('address') }}">
                                        <label for="address">Địa chỉ *</label>
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="country" value="Việt Nam">
                            </div>
                        @endif
                    </div>
                    <div class="checkout__totals-wrapper">
                        <div class="sticky-content">
                            <div class="checkout__totals">
                                <h3>Đơn hàng của bạn</h3>
                                <table class="checkout-cart-items">
                                    <thead>
                                        <tr>
                                            <th>SẢN PHẨM</th>
                                            <th align="right">GIÁ TRỊ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->name }} x {{ $item->qty }}
                                                </td>
                                                <td align="right">
                                                    ${{ $item->subtotal() }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (Session::has('coupon'))
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>GIÁ TRỊ</th>
                                                <td align="right">${{ Cart::instance('cart')->subtotal() }}</td>
                                            </tr>
                                            <tr>
                                                <th>VẬN CHUYỂN</th>
                                                <td align="right">Miễn phí vận chuyển</td>
                                            </tr>
                                            <tr>
                                                <th>KHUYẾN MẠI</th>
                                                <td align="right">${{ Session::get('discounts')['discount'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>TỔNG TIỀN</th>
                                                <td align="right">${{ Session::get('discounts')['after_discount'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>GIÁ TRỊ</th>
                                                <td align="right">${{ Cart::instance('cart')->subtotal() }}</td>
                                            </tr>
                                            <tr>
                                                <th>VẬN CHUYỂN</th>
                                                <td align="right">Miễn phí vận chuyển</td>
                                            </tr>
                                            <tr>
                                                <th>TỔNG TIỀN</th>
                                                <td align="right">${{ Cart::instance('cart')->subtotal() }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="checkout__payment-methods">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode1" value="CARD">
                                    <label class="form-check-label" for="mode1">
                                        Chuyển khoản ngân hàng trực tiếp
                                        <p class="option-detail">
                                            Thực hiện thanh toán trực tiếp vào tài khoản ngân hàng của chúng tôi. Vui lòng
                                            sử dụng ID đặt hàng của bạn như thanh toán tham chiếu. Đơn hàng của bạn sẽ không
                                            được vận chuyển cho đến khi các khoản tiền đã được xóa trong tài khoản.
                                        </p>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode2" checked value="COD">
                                    <label class="form-check-label" for="mode2">
                                        Thanh toán khi nhận hàng
                                        <p class="option-detail">
                                            COD (Cash on Delivery) là phương thức thanh toán khi khách hàng trả tiền mặt
                                            sau khi nhận hàng. Đây là lựa chọn phổ biến trong mua sắm trực tuyến, giúp tăng
                                            sự tin tưởng vì không cần trả trước.
                                        </p>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode3" value="PAYPAL">
                                    <label class="form-check-label" for="mode3">
                                        Paypal
                                        <p class="option-detail">
                                            PayPal là phương thức thanh toán trực tuyến an toàn, cho phép người dùng thanh
                                            toán, chuyển và nhận tiền qua internet mà không cần chia sẻ thông tin tài chính
                                            với người bán.
                                        </p>
                                    </label>
                                </div>
                                <div class="policy-text">
                                    Dữ liệu cá nhân của bạn sẽ được sử dụng để xử lý đơn đặt hàng của bạn, hỗ trợ trải
                                    nghiệm của bạn trên toàn trang web này và cho các mục đích khác được mô tả trong
                                    <a href="terms.html" target="_blank">chính sách bảo mật</a> của chúng tôi.
                                </div>
                            </div>
                            <button class="btn btn-primary btn-checkout">ĐẶT HÀNG</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection
