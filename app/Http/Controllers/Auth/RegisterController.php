<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'digits:10', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Tên không được bỏ trống',
            'name.string' => 'Tên phải là một chuỗi ký tự',
            'name.max' => 'Tên không vượt quá 255 ký tự',
            'email.required' => 'Email không được bỏ trống',
            'email.string' => 'Email phải là một chuỗi ký tự',
            'email.email' => 'Email phải dưới dạng @gmail.com',
            'email.max' => 'Email không vượt quá 255 ký tự',
            'email.unique' => 'Email đã tồn tại',
            'mobile.required' => 'Số điện thoại không được bỏ trống',
            'mobile.digits' => 'Số điện thoại phải là 10 chữ số',
            'mobile.unique' => 'Số điện thoại đã tồn tại',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'password.string' => 'Mật khẩu phải là một chuỗi ký tự',
            'password.min' => 'Mật khẩu phải lớn hơn 8 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không đúng'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
