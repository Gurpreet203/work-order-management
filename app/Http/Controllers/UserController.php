<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Notifications\SetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::with(['role', 'assigned_to'])
                ->visibleTo()
                ->search(request(['search']))
                ->paginate()
        ]);
    }

    public function create()
    {
        if (request('type') == 'employee')
        {
            $managers = User::managers()
                ->get();
        }
        else
        {
            $managers = null;
        }

        return view('users.create', [
            'managers' => $managers
        ]);
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'first_name' => 'required|min:3|max:255|alpha',
            'last_name' => 'required|min:3|max:255|alpha',
            'email' => 'required|email:rfc,dns|unique:users',
            'user_id' => ['sometimes', 'required', 
                Rule::in(
                    User::managers()
                        ->pluck('id')
                        ->toArray()
                )],
        ]);

        if ($request->has('user_id'))
        {
            $attributes += [
                'role_id' => Role::EMPLOYEE
            ];
        }
        else
        {
            $attributes += [
                'role_id' => Role::MANAGER,
                'user_id' => Role::ADMIN
            ];
        }
        $user = User::create($attributes);
        
        Notification::send($user, new SetPasswordNotification(Auth::user()));
        
        return to_route('users.edit', $user)->with('success', 'User Created Successfully');
    }

    public function edit(User $user)
    {
        if ($user->role_id == Role::EMPLOYEE)
        {
            $managers = User::managers()
                ->get();
        }
        else
        {
            $managers = null;
        }

        return view('users.edit', [
            'managers' => $managers,
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $attributes = $request->validate([
            'first_name' => 'required|min:3|max:255|alpha',
            'last_name' => 'required|min:3|max:255|alpha',
            'user_id' => ['sometimes', 'required', 
                Rule::in(
                    User::managers()
                        ->pluck('id')
                        ->toArray()
                )],
        ]);

        $user->update($attributes);

        return back()->with('success', 'Successfully Updated');
    }
}
