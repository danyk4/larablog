<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class UserController extends Controller
{
    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user     = auth()->user();
        $filename = $user->id.'-'.uniqid().'.jpg';

        $imgData = Image::read($request->file('avatar'))->scale(120)->encodeByExtension('jpg');
        Storage::disk('public')->put('avatars/'.$filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != '/fallback-avatar.jpg') {
            Storage::disk('public')->delete(str_replace('/storage/', '/', $oldAvatar));
        }

        return redirect('/profile/'.$user->username)->with('success', 'Avatar updated.');
    }

    public function manageAvatar()
    {
        return view('avatar-form');
    }

    public function profile($user)
    {
        $currentlyFollowing = 0;
        $username           = User::query()->where('username', $user)->firstOrFail();
        $posts              = $username->posts()->orderBy('created_at', 'desc')->get();

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $username->id]])->count();
        }

        return view(
            'profile-posts',
            [
                'posts'              => $posts,
                'username'           => $username,
                'currentlyFollowing' => $currentlyFollowing,
                //'followerCount'      => count($username->followers),
            ]
        );
    }

    public function profileFollowers($user)
    {
        $currentlyFollowing = 0;
        $username           = User::query()->where('username', $user)->firstOrFail();
        $posts              = $username->posts()->orderBy('created_at', 'desc')->get();

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $username->id]])->count();
        }

        return view(
            'profile-followers',
            [
                'posts'              => $posts,
                'username'           => $username,
                'currentlyFollowing' => $currentlyFollowing,
                'followers'          => $username->followers()->latest()->get(),
            ]
        );
    }

    public function profileFollowing($user)
    {
        $currentlyFollowing = 0;
        $username           = User::query()->where('username', $user)->firstOrFail();
        $posts              = $username->posts()->orderBy('created_at', 'desc')->get();

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $username->id]])->count();
        }

        return view(
            'profile-following',
            [
                'posts'              => $posts,
                'username'           => $username,
                'currentlyFollowing' => $currentlyFollowing,
                'following'          => $username->followingUsers()->latest()->get(),
            ]
        );
    }

    public function correctHomePage()
    {
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(3)]);
        } else {
            return view('homepage');
        }
    }

    public function register(Request $request)
    {
        $registerFields = $request->validate([
            'username' => 'required|string|min:3|max:24|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
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
