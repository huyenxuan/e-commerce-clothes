<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
}
