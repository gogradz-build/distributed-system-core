<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login()
    {
        $user = Auth::user();
        if ($user == null) {
            return Inertia::render('Admin/Auth/Login');
        } else {
            return redirect()->back();
        }
    }

    public function signIn(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $roles = $user->getRoleNames();
            
            if ($roles->contains('Super Admin') || $roles->contains('Ref') ) {
                return redirect()->to('http://127.0.0.1:8000/admin/dashboard');
            }
            return redirect()->intended('dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
