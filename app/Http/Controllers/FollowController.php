<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user)
    {
        // can't follow yourself
        if ($user->id == auth()->user()->id) {
            return back()->with('error', 'You cannot follow yourself');
        }

        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        //dd(Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]]));
        // can't follow if you already are
        if ($existCheck) {
            return back()->with('error', 'You are already following this user');
        }

        $newFollow               = new Follow();
        $newFollow->user_id      = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('success', 'Followed');
    }

    public function removeFollow(User $user)
    {
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();

        return back()->with('success', 'Unfollowed');
    }
}
