<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;


class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->usertype === 'ADM') {
                return $next($request);
            } else {
                Session::flush(); // xóa toàn bộ session
                // Cookie::flush();
                return redirect()->route('login')->with('success', 'Đăng xuất thành công');
            }
        } else {
            return redirect()->route('login')->with('error', 'Đã xảy ra lỗi. Vui lòng đăng nhập lại');
        }
    }
}
