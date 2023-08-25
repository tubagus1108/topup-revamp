<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.login');
    }

    public function index()
    {
        // Kode untuk halaman dashboard
        return view('admin.dashboard');
    }
}
