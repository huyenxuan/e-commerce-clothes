@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Danh mục yêu thích</h2>
            <div class="shopping-cart">
                @if ($items->count() > 0)
                    <div class="cart-table__wrapper py-0">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th></th>
                                    <th>Giá thành</th>
                                    <th>Số lượng</th>
                                    <th>Hành động</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <img loading="lazy"
                                                    src="{{ asset('uploads/products') }}/{{ $item->model->image }}"
                                                    width="120" height="120" alt="{{ $item->name }}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <h4>{{ $item->name }}</h4>
                                                <ul class="shopping-cart__product-item__options">
                                                    <li>Màu sắc: Yellow</li>
                                                    <li>Kích thước: L</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__product-price">${{ $item->price }}</span>
                                        </td>
                                        <td>
                                            {{ $item->qty }}
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-6">
                                                    <form
                                                        action="{{ route('wishlist.item.move_to_cart', ['rowId' => $item->rowId]) }}"
                                                        method="post">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning">
                                                            Chuyển đến Giỏ hàng
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="col-6">
                                                    <form
                                                        action="{{ route('wishlist.item.remove', ['rowId' => $item->rowId]) }}"
                                                        method="post" id="remove-item">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="javascript:void(0)" class="remove-cart"
                                                            onclick="document.getElementById('remove-item').submit()">
                                                            <svg width="10" height="10" viewBox="0 0 10 10"
                                                                fill="#767676" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                                <path
                                                                    d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                            </svg>
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="cart-table-footer">
                        <button class="btn btn-light">Chuyển đến Giỏ hàng</button>
                    </div> --}}
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <p>Không có sản phẩm nào trong danh mục yêu thích</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
