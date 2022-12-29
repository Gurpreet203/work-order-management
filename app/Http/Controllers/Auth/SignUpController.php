<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    public function create()
    {
        return view('users.signUp');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'first_name' => 'required|min:3|max:255|alpha',
            'last_name' => 'required|min:3|max:255|alpha',
            'email' => 'required|email:rfs,dns|unique:users',
            'password' => 'required|min:8|max:255'
        ]);
        $attributes += [
            'role_id' => Role::CUSTOMER
        ];
        $attributes['password'] = Hash::make($attributes['password']);

        User::create($attributes);

        if (Auth::attempt($attributes))
        {
            return redirect('/');
        }

        return back()->with('error', 'Something went wrong');
    }
}
