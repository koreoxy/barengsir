<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home view for regular users.
     */
    public function index(): View
    {
        return view('home');
    }
}
