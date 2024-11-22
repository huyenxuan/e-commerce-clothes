<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Contact;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::orderBy('id', 'desc')->take(3)->get();
        $categories = Category::orderBy('id', 'desc')->get();
        $sproducts = Product::whereNotNull('sale_price')
            ->where('sale_price', '<>', '')
            ->whereColumn('sale_price', '<', 'regular_price')
            ->inRandomOrder()
            ->limit(8)
            ->get();
        $fproducts = Product::where('featured', 1)->inRandomOrder()->limit(10)->get();
        return view('index', compact('slides', 'categories', 'sproducts', 'fproducts'));
    }
    // about
    public function about()
    {
        return view('about');
    }

    // contact
    public function contact()
    {
        return view('contact');
    }
    // contact store
    public function contact_store(Request $request)
    {
        // Validate form data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'message' => 'required|string'
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Vui lòng nhập email hợp lệ',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.numeric' => 'Vui lòng nhập số điện thoại hợp lệ',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn'
        ]);

        // Store contact form data
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();

        // Redirect to contact success page
        return redirect()->route('contact.index')->with('success', 'Nội dung của bạn đã được gửi. Chúng tôi sẽ sớm liên hệ với bạn.');
    }

    // privacy policy
    public function privacy_policy()
    {
        return view('privacy-policy');
    }

    // terms and conditions
    public function terms_conditions()
    {
        return view('terms-conditions');
    }

    // search
    public function search(Request $request)
    {
        $query = $request->input('query');
        $result = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('regular_price', 'LIKE', "%{$query}%")
            ->orWhere('sale_price', 'LIKE', "%{$query}%")
            ->take(8)->get();
        return response()->json($result);
    }
}
