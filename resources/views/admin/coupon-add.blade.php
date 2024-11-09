@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thông tin mã giảm giá</h3>
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
                        <a href="{{ route('admin.coupons') }}">
                            <div class="text-tiny">Mã giảm giá</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Mã giảm giá mới</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.coupon.store') }}">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Mã giảm giá <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Mã giảm giá" name="code" tabindex="0"
                            value="" aria-required="true" required="">
                    </fieldset>
                    @error('code')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="category">
                        <div class="body-title">Loại mã giảm giá</div>
                        <div class="select flex-grow">
                            <select class="" name="type">
                                <option value="">Select</option>
                                <option value="Cố định">Cố định</option>
                                <option value="Phần trăm">Phần trăm</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('type')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Giá trị <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Giá trị" name="value" tabindex="0"
                            value="" aria-required="true" required="">
                    </fieldset>
                    @error('value')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Giá trị đơn hàng <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Giá trị đơn hàng" name="cart_value"
                            tabindex="0" value="" aria-required="true" required="">
                    </fieldset>
                    @error('cart_value')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Ngày hết hạn <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="date" placeholder="Ngày hết hạn" name="expiry_date" tabindex="0"
                            value="" aria-required="true" required="">
                    </fieldset>
                    @error('expiry_date')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Thêm mã</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
