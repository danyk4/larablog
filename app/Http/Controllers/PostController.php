<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function search($term)
    {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar');

        return $posts;
    }

    public function show(Post $post): View
    {
        $post['body'] = strip_tags(Str::markdown($post['body']), '<p><a><h1><h2><h3><h4><h5><ul><li><ol><strong><em><br>');

        return view('show', compact('post'));
    }

    public function createPost(): mixed
    {
//        if (!auth()->check()) {
//            return redirect('/')->with('error', 'You have to be logged in to write posts.');
//        }

        return view('create-post');
    }

    public function storePost(Request $request): RedirectResponse
    {
        $formFields = $request->validate([
            'title' => 'required|min:5|max:255',
            'body'  => 'required|min:5 ',
        ]);

        $formFields['title'] = strip_tags($formFields['title']);
        $formFields['body']  = strip_tags($formFields['body']);

        $formFields['user_id'] = auth()->id();

        $newPost = Post::create($formFields);

        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'name'   => auth()->user()->username,
            'title'  => $newPost->title,
        ]));

        return redirect("/post/{$newPost->id}")->with('success', 'Post created!');
    }

    public function storeNewPostApi(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required|min:5|max:255',
            'body'  => 'required|min:5 ',
        ]);

        $formFields['title'] = strip_tags($formFields['title']);
        $formFields['body']  = strip_tags($formFields['body']);

        $formFields['user_id'] = auth()->id();

        $newPost = Post::create($formFields);

        dispatch(new SendNewPostEmail([
            'sendTo' => auth()->user()->email,
            'name'   => auth()->user()->username,
            'title'  => $newPost->title,
        ]));

        return $newPost->id;
    }

    public function edit(Post $post)
    {
        return view('edit-post', compact('post'));
    }

    public function update(Post $post, Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required|min:5|max:255',
            'body'  => 'required|min:5 ',
        ]);

        $formFields['title'] = strip_tags($formFields['title']);
        $formFields['body']  = strip_tags($formFields['body']);

        $post->update($formFields);

        return redirect("/post/{$post->id}")->with('success', 'Post updated!');
    }

    public function destroy(Post $post)
    {
//        if (auth()->user()->cannot('delete', $post)) {
//            return 'You are not allowed to delete this post!';
//        }

        $post->delete();

        return redirect("/profile/".auth()->user()->username)->with('success', 'Post deleted!');
    }

    public function deleteApi(Post $post)
    {
        $post->delete();

        return 'true';
    }
}
