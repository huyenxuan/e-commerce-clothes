<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
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
