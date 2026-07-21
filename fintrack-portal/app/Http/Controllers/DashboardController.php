<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard home page.
     */
    public function index()
    {
        return view('dashboard.index');
    }
}
