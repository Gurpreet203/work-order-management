<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    public function index(User $user)
    {
        return view('users.initialPassword', [
            'user' => $user
        ]);
    }

    public function store(User $user, Request $request)
    {
        $attributes = $request->validate([
            'password' => 'required|min:8|max:255',
            'confirm-password' => 'required|min:8|max:255|same:password',
            'email' => 'required'
        ]);

        if ($user->password == null)
        {
            $user->update([
                'password' => Hash::make($attributes['password']),
                'status' => true,
                'email_status' => true
            ]);
    
            Auth::attempt($attributes);

            return redirect('/');
        }

        return back()->with('error', 'You already set the password to change password please contact admin or forget password');
    }
}

