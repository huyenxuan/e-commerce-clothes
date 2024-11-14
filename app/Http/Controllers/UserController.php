<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // khởi tạo hàm index
    public function index()
    {
        return view('user.index');
    }
    // orders
    public function orders()
    {
        $userId = Auth::user()->id;
        $orders = Order::where('user_id', $userId)->orderBy('created_at', 'desc')->paginate(12);
        return view('user.orders', compact('orders'));
    }
    // order details
    public function order_details($id)
    {
        $order = Order::where('user_id', Auth::user()->id)->where('id', $id)->first();
        if ($order) {
            $orderItems = OrderItem::where('order_id', $order->id)->orderBy('id', 'desc')->paginate(12);
            $transaction = Transaction::where('order_id', $order->id)->first();
        } else {
            return redirect()->route('login');
        }
        return view('user.order-details', compact('order', 'orderItems', 'transaction'));
    }
    // cancel order
    public function cancel_order(Request $request)
    {
        $order = Order::find($request->order_id);
        if ($order) {
            $order->status = 'Đã hủy';
            $order->canceled_date = Carbon::now();
            $order->save();
            return redirect()->route('user.orders')->with('success', 'Đơn hàng đã bị hủy thành công');
        } else {
            return redirect()->route('login');
        }
    }
}
