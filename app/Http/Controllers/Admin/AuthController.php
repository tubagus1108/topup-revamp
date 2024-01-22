<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
{
    if (Auth::check()) {
        return redirect('/admin/dashboard'); // Ganti '/dashboard' dengan URL yang sesuai
    }

    return view('admin.auth.login');
}

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // dd($user);
            if ($user->role == 'Admin') { // Misalkan isAdmin() adalah metode dalam model User untuk memeriksa peran "Admin"
                return redirect()->intended('/admin/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'You do not have permission to access.']);
            }
        }

        return back()->withErrors(['email' => 'Credentials do not match.']);
    }


    public function logout()
    {
        Auth::logout();
        return redirect('/admin/auth/login');
    }
}
