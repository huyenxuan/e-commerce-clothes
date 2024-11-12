@extends('layouts.app')
@section('title')
    Trang người dùng
@endsection
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Tài khoản của tôi</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <p>Xin chào <strong>User</strong></p>
                        <p>
                            Từ bảng điều khiển tài khoản của bạn, bạn có thể xem <a href="">các đơn đặt hàng gần đây
                                của mình</a>, quản lý <a href="">địa chỉ vận chuyển</a> và <a href="">chỉnh sửa
                                mật khẩu và chi tiết tài khoản của bạn</a>.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
