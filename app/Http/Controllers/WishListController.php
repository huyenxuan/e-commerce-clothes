<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class WishListController extends Controller
{
    // index
    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist', compact('items'));
    }
    // add to wish list
    public function add_to_wishlist(Request $request)
    {
        Cart::instance('wishlist')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back()->with('success', 'Thêm sản phẩm vào danh sách yêu thích thành công');
    }
    // remove from wish list
    public function remove_from_wishlist($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back()->with('success', 'Xóa sản phẩm khỏi danh sách yêu thích thành công');
        ;
    }
    // move to cart
    public function move_to_cart($rowId)
    {
        $item = Cart::instance('wishlist')->get($rowId);
        Cart::instance('cart')->add($item->id, $item->name, $item->qty, $item->price)->associate('App\Models\Product');
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back()->with('success', 'Chuyển sản phẩm đến giỏ hàng thành công');
        ;
    }
}
