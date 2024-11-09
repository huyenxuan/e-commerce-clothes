<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // index
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    // add to cart
    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back()->with('success', 'Thêm vào giỏ hàng thành công');
    }
    // increase quantity
    public function increase_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);

        return redirect()->back()->with('success', 'Tăng sản phẩm thành công');
    }
    // descrease quantity
    public function decrease_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        if ($qty < 1) {
            Cart::instance('cart')->remove($rowId);
        } else {
            Cart::instance('cart')->update($rowId, $qty);
        }

        return redirect()->back()->with('success', 'Xóa sản phẩm thành công');
    }
    // remove item
    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công');
    }
    // clear cart
    public function clear_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back()->with('success', 'Xóa giỏ hàng thành công');
    }

    // apply coupon
    public function apply_coupon(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', Cart::instance('cart')->subtotal())->first();

            if (!$coupon) {
                return redirect()->back()->with('error', 'Mã khuyến mại không tồn tại hoặc đã hết hạn');
            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value,
                ]);
                $this->calculate_discount();
                return redirect()->back()->with('success', 'Áp mã khuyến mại thành công');
            }
        } else {
            return redirect()->back()->with('error', 'Vui lòng nhập mã khuyến mại');
        }
    }
    // caculate discount
    public function calculate_discount()
    {
        $discount = 0;
        if (Session::has('coupon')) {
            if (Session::get('coupon')['type'] === 'Cố định') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = Session::get('coupon')['value'] * Cart::instance('cart')->subtotal() / 100;
            }
            $totalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
            Session::put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'after_discount' => number_format(floatval($totalAfterDiscount), 2, '.', '')
            ]);
        }
    }
    // removing coupon code 
    public function remove_coupon()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return redirect()->back()->with('success', 'Xóa mã giảm giá thành công');
    }
}
