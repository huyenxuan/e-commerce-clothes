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

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
// shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product.details');

// cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_quantity'])->name('cart.qty.increase');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_quantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/item/{rowId}', [CartController::class, 'remove_item'])->name('cart.item.remove');
Route::delete('/cart/clear', [CartController::class, 'clear_cart'])->name('cart.clear');
Route::post('/cart/apply-coupon', [CartController::class, 'apply_coupon'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon', [CartController::class, 'remove_coupon'])->name('cart.coupon.remove');

// checkout
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class, 'place_an_order'])->name('cart.place_an_order');
Route::get('/order-complete', [CartController::class, 'order_complete'])->name('cart.order_complete');

// search

// wishlist
Route::get('/wishlist', [WishListController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishListController::class, 'add_to_wishlist'])->name('wishlist.add');
Route::delete('/wishlist/remove/item/{rowId}', [WishListController::class, 'remove_from_wishlist'])->name('wishlist.item.remove');
Route::post('wishlist/move-to-cart/{rowId}', [WishListController::class, 'move_to_cart'])->name('wishlist.item.move_to_cart');

// about
Route::get('/about', [HomeController::class, 'about'])->name('about.index');

// contact
Route::get('/contact', [HomeController::class, 'contact'])->name('contact.index');

// privacy policy
Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('privacy_policy.index');

// terms conditions
Route::get('/terms-conditions', [HomeController::class, 'terms_conditions'])->name('terms_conditions.index');

// authenticated 
// user
Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
});
// admin
Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    // brand
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'brand_add'])->name('admin.brand.add');
    Route::post('/admin/brands/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('/admin/brands/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brands/delete/{id}', [AdminController::class, 'brand_delete'])->name('admin.brand.delete');

    // category
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class, 'category_add'])->name('admin.category.add');
    Route::post('/admin/categories/store', [AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('/admin/categories/update', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/categories/delete/{id}', [AdminController::class, 'category_delete'])->name('admin.category.delete');

    // product
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'product_add'])->name('admin.product.add');
    Route::post('/admin/products/store', [AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('/admin/products/update', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/products/delete/{id}', [AdminController::class, 'product_delete'])->name('admin.product.delete');

    // coupon
    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupons/add', [AdminController::class, 'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupons/store', [AdminController::class, 'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupons/edit/{id}', [AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
    Route::put('/admin/coupons/update', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
    Route::delete('/admin/coupons/delete/{id}', [AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

    // order
    Route::get('/admin/orders', [AdminController::class, 'orders'])->name(name: 'admin.orders');
    Route::get('/admin/order/details/{id}', [AdminController::class, 'order_details'])->name('admin.order.details');
});