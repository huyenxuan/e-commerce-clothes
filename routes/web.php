<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishListController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AboutController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
// shop
Route::get('/cua-hang', [ShopController::class, 'index'])->name('shop.index');
Route::get('/cua-hang/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');

// cart
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('/gio-hang/tang-so-luong/{rowId}', [CartController::class, 'increase_quantity'])->name('cart.qty.increase');
Route::put('/gio-hang/giam-so-luong/{rowId}', [CartController::class, 'decrease_quantity'])->name('cart.qty.decrease');
Route::delete('/gio-hang/xoa/item/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
Route::delete('/gio-hang/xoa-toan-bo', [CartController::class, 'clear_cart'])->name('cart.clear');
Route::post('/gio-hang/ap-ma-giam-gia', [CartController::class, 'apply_coupon'])->name('cart.coupon.apply');
Route::delete('/gio-hang/xoa-ma-giam-gia', [CartController::class, 'remove_coupon'])->name('cart.coupon.remove');

// checkout
Route::get('/thanh-toan', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/xac-nhan', [CartController::class, 'place_an_order'])->name('cart.place_an_order');
Route::get('/hoan-tat', [CartController::class, 'order_complete'])->name('cart.order_complete');

// search

// wishlist
Route::get('/danh-sach-mong-muon', [WishListController::class, 'index'])->name('wishlist.index');
Route::post('/danh-sach-mong-muon/them', [WishListController::class, 'add_to_wishlist'])->name('wishlist.add');
Route::delete('/danh-sach-mong-muon/xoa/item/{rowId}', [WishListController::class, 'remove_from_wishlist'])->name('wishlist.item.remove');
Route::post('/danh-sach-mong-muon/chuyen-sang-gio-hang/{rowId}', [WishListController::class, 'move_to_cart'])->name('wishlist.item.move_to_cart');

// about
Route::get('/ve-chung-toi', [HomeController::class, 'about'])->name('about.index');

// contact
Route::get('/lien-he', [HomeController::class, 'contact'])->name('contact.index');
Route::post('/lien-he/gui', [HomeController::class, 'contact_store'])->name('contact.store');

// privacy policy
Route::get('/chinh-sac-rieng-tu', [HomeController::class, 'privacy_policy'])->name('privacy_policy.index');

// terms conditions
Route::get('/dieu-khoan-dieu-kien', [HomeController::class, 'terms_conditions'])->name('terms_conditions.index');

// search
Route::get('/tim-kiem', [HomeController::class, 'search'])->name('home.search');

// account
Route::get('/dang-nhap', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/dang-nhap', [LoginController::class, 'login']);
Route::post('/dang-xuat', [LoginController::class, 'logout'])->name('logout');

// authenticated 
// user
Route::middleware(['auth'])->group(function () {
    Route::get('/tai-khoan', [UserController::class, 'index'])->name('user.index');
    Route::get('/don-hang', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/don-hang/chi-tiet/{id}', [UserController::class, 'order_details'])->name('user.orders.details');
    Route::put('/don-hang/huy-don', [UserController::class, 'cancel_order'])->name('user.order.cancel_order');
});
// admin
Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // brand
    Route::get('/admin/thuong-hieu', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/thuong-hieu/them-moi', [AdminController::class, 'brand_add'])->name('admin.brand.add');
    Route::post('/admin/thuong-hieu/luu', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/thuong-hieu/chinh-sua/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/thuong-hieu/cap-nhat', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/thuong-hieu/xoa-thuong-hieu/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');

    // category
    Route::get('/admin/danh-muc', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/danh-muc/them-moi', [AdminController::class, 'category_add'])->name('admin.category.add');
    Route::post('/admin/danh-muc/luu', [AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/danh-muc/chinh-sua/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('/admin/danh-muc/cap-nhat', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/danh-muc/xoa-danh-muc/{id}', [AdminController::class, 'category_delete'])->name('admin.category.delete');

    // product
    Route::get('/admin/san-pham', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/san-pham/them-moi', [AdminController::class, 'product_add'])->name('admin.product.add');
    Route::post('/admin/san-pham/luu', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/san-pham/chinh-sua/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('/admin/san-pham/cap-nhat', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/san-pham/xoa/{id}', [AdminController::class, 'product_delete'])->name('admin.product.delete');

    // coupon
    Route::get('/admin/ma-giam-gia', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/ma-giam-gia/them-moi', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/ma-giam-gia/luu', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/ma-giam-gia/chinh-sua/{id}', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
    Route::put('/admin/ma-giam-gia/cap-nhat', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
    Route::delete('/admin/ma-giam-gia/xoa/{id}', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

    // order
    Route::get('/admin/don-hang', [AdminController::class, 'orders'])->name(name: 'admin.orders');
    Route::get('/admin/don-hang/chi-tiet/{id}', [AdminController::class, 'order_details'])->name('admin.order.details');
    Route::put('/admin/don-hang/cap-nhat-trang-thai', [AdminController::class, 'order_update_status'])->name('admin.order.update_status');

    // slides
    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slide/them-moi', [AdminController::class, 'slide_add'])->name('admin.slide.add');
    Route::post('/admin/slide/luu', [AdminController::class, 'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/chinh-sua/{id}', [AdminController::class, 'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slides/cao-nhat', [AdminController::class, 'slide_update'])->name('admin.slide.update');
    Route::delete('/admin/slides/xoa/{id}', [AdminController::class, 'slide_delete'])->name('admin.slide.delete');
});