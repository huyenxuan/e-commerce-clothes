<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
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
}
