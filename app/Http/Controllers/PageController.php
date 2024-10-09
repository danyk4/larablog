<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function homePage()
    {
        return view('homepage');
    }

    public function aboutPage()
    {
        return view('single-post');
    }
}
