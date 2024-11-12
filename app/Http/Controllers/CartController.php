<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
        $this->calculate_discount();
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
        $this->calculate_discount();
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công');
    }
    // remove item
    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        $this->calculate_discount();
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công');
    }
    // clear cart
    public function clear_cart()
    {
        Cart::instance('cart')->destroy();
        $this->calculate_discount();
        return redirect()->back()->with('success', 'Xóa giỏ hàng thành công');
    }

    // apply coupon
    public function apply_coupon(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', Cart::instance('cart')->subtotal())
                ->first();
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
            $couponValue = floatval(Session::get('coupon')['value']);
            $subtotal = floatval(preg_replace('/[^\d.]/', '', Cart::instance('cart')->subtotal()));
            if (Session::get('coupon')['type'] === 'Cố định') {
                $discount = $couponValue;
            } else {
                $discount = $couponValue * $subtotal / 100;
            }
            $totalAfterDiscount = $subtotal - $discount;
            Session::put('discounts', [
                'discount' => number_format($discount, 2, '.', ''),
                'subtotal' => $subtotal,
                'after_discount' => number_format($totalAfterDiscount, 2, '.', '')
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

    // checkout
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để thực hiện chức năng này');
        }
        $address = Address::where('user_id', Auth::user()->id)->where('is_default', true)->first();
        $items = Cart::instance('cart')->content();
        return view('checkout', compact('address', 'items'));
    }

    // order
    public function place_an_order(Request $request)
    {
        // gọi ra user và address
        $user_id = Auth::user()->id;
        $address = Address::where('user_id', $user_id)->where('is_default', true)->first();

        if (!$address) {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'city' => 'required',
                'state' => 'required',
                'locality' => 'required',
                'address' => 'required|max:255',
            ], [
                'name.required' => 'Vui lòng nhập họ tên',
                'name.max' => 'Họ tên không quá 100 ký tự',
                'phone.required' => 'Vui lòng nhập số điện thoại',
                'phone.numeric' => 'Số điện thoại phải là số',
                'phone.digits' => 'Số điện thoại phải có 10 chữ số',
                'city.required' => 'Vui lòng nhập thành phố',
                'state.required' => 'Vui lòng nhập quận huyện',
                'locality.required' => 'Vui lòng nhập phường xã',
                'address.required' => 'Vui lòng nhập địa chỉ',
                'address.max' => 'Địa chỉ không quá 255 ký tự',
            ]);

            // gọi địa chỉ
            $address = new Address();
            $address->user_id = $user_id;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->locality = $request->locality;
            $address->address = $request->address;
            $address->country = $request->country;
            $address->is_default = true;
            $address->save();
        }
        $this->setAmountForCheckout();

        // tạo đơn hàng
        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = Session::get('checkout')['sub_total'];
        $order->after_discount = Session::get('checkout')['after_discount'];
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->address = $address->address;
        $order->locality = $address->locality;
        $order->save();

        // tạo chi tiết đơn hàng
        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->quantity = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->save();
        }

        // vận chuyển
        if ($request->mode == 'COD') {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = 'Chờ vận chuyển';
            $transaction->save();
        } else if ($request->mode == 'CARD') {
            return;
        } else {
            return;
        }

        // xóa giỏ hàng
        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('order_id', $order->id);
        return redirect()->route('cart.order_complete')->with('success', 'Đặt hàng thành công. Chúng tôi sẽ liên lạc với bạn sớm nhất');
    }

    // thanh toán
    public function setAmountForCheckout()
    {
        if (!Cart::instance('cart')->content()->count() > 0) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'sub_total' => Session::get('discounts')['subtotal'],
                'after_discount' => Session::get('discounts')['after_discount'],
                // 'total' => Session::get('discounts')['subtotal'] - Session::get('discounts')['discount'],
                // 'subtotal' => Session::get('discounts')['subtotal']
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'sub_total' => Cart::instance('cart')->subtotal(),
                'after_discount' => Cart::instance('cart')->subtotal(),
                // 'total' => Cart::instance('cart')->subtotal(),
                // 'subtotal' => Cart::instance('cart')->subtotal()
            ]);
        }
    }

    // order completed
    public function order_complete()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            return view('order-complete', compact('order'));
        }
        return view('cart.index');
    }
}
