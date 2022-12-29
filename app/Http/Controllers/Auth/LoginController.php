<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('users.login');
    }

    public function login(Request $request)
    {
        $attributes = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|max:255'
        ]);

        if (Auth::attempt($attributes))
        {
            return redirect('/');
        }

        return back()->with('error', 'credentials are not correct');
    }

    public function logout()
    {
        Auth::logout();

        return to_route('login.index');
    }
}
