<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // khởi tạo hàm index
    public function index()
    {
        return view('user.index');
    }
}
