<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function correctHomePage()
    {
        if (auth()->check()) {
            return view('homepage-feed');
        } else {
            return view('homepage');
        }
    }
    public function register(Request $request)
    {
        $registerFields = $request->validate([
            'username' => 'required|string|min:3|max:24|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:24|confirmed',
        ]);

        $registerFields['password'] = bcrypt($registerFields['password']);

        $user = User::create($registerFields);

        auth()->login($user); // user will be logged automatically with adding session

        return redirect('/')->with('success', 'Thank you for creating an account. Please verify your email.');
    }

    public function login(Request $request)
    {
        $loginFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required',
        ]);

        if (auth()->attempt([
            'username' => $loginFields['loginusername'],
            'password' => $loginFields['loginpassword'],
        ])) {
            $request->session()->regenerate();

            return redirect('/')->with('success', 'You are now logged in');
        } else {
            return redirect('/')->with('error', 'Wrong username or password');
        }
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/')->with('success', 'You are now logged out');
    }
}
