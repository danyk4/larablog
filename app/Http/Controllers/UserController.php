<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            ]
        );
    }

    public function profileRaw($user)
    {
        $currentlyFollowing = 0;
        $username           = User::query()->where('username', $user)->firstOrFail();
        $posts              = $username->posts()->orderBy('created_at', 'desc')->get();

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $username->id]])->count();
        }

        return response()->json([
            'theHTML'  => view('profile-posts-only', ['posts' => $posts])->render(),
            'docTitle' => $username->username."'s profile",
        ]);
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

    public function profileFollowersRaw($user)
    {
        $currentlyFollowing = 0;
        $username           = User::query()->where('username', $user)->firstOrFail();
        $posts              = $username->posts()->orderBy('created_at', 'desc')->get();

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $username->id]])->count();
        }

        return response()->json([
            'theHTML'  => view('profile-followers-only', [
                'posts'              => $posts,
                'currentlyFollowing' => $currentlyFollowing,
                'followers'          => $username->followers()->latest()->get(),
            ])->render(),
            'docTitle' => $username->username."'s followers",
        ]);
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

    public function profileFollowingRaw($user)
    {
        $currentlyFollowing = 0;
        $username           = User::query()->where('username', $user)->firstOrFail();
        $posts              = $username->posts()->orderBy('created_at', 'desc')->get();

        if (auth()->check()) {
            $currentlyFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $username->id]])->count();
        }

        return response()->json([
            'theHTML'  => view('profile-following-only', [
                'posts'              => $posts,
                'currentlyFollowing' => $currentlyFollowing,
                'following'          => $username->followingUsers()->latest()->get(),
            ])->render(),
            'docTitle' => $username->username."'s following users",
        ]);
    }

    public function correctHomePage()
    {
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(3)]);
        } else {
            $postCount = Cache::remember('postCount', 20, function () {
                return Post::count();
            });

            return view('homepage', ['postCount' => $postCount]);
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

    public function loginApi(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt($incomingFields)) {
            $user  = User::where('username', $incomingFields['username'])->firstOrFail();
            $token = $user->createToken('larablogtoken')->plainTextToken;

            return $token;
        }

        return '';
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/')->with('success', 'You are now logged out');
    }
}
