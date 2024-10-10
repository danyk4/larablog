<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $registerFields = $request->validate([
            'username' => 'required|string|min:3|max:24|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:24|confirmed',
        ]);

        $registerFields['password'] = bcrypt($registerFields['password']);

        User::create($registerFields);

        return 'User created';
    }
}
