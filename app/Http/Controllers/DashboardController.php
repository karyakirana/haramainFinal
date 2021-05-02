<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display Dashboard Utama
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.dashboard');
    }
}
