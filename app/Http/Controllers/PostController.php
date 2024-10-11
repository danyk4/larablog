<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Post $post)
    {
        return view('show', compact('post'));
    }
    public function createPost()
    {
        return view('create-post');
    }

    public function storePost(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required|min:5|max:255',
            'body' => 'required|min:5 ',
        ]);

        $formFields['title'] = strip_tags($formFields['title']);
        $formFields['body'] = strip_tags($formFields['body']);

        $formFields['user_id'] = auth()->id();

        $newPost = Post::create($formFields);

        return redirect("/post/{$newPost->id}")->with('success', 'Post created!');
    }
}
