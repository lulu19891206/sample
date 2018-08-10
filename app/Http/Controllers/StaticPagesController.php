<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function home()
    {
    	# code...
    	return view('static_pages/home');
    }

    public function help()
    {
    	# code...
    	return view('static_pages/help');
    }

    public function about()
    {
    	# code...
    	return view('static_pages/about');
    }
}
