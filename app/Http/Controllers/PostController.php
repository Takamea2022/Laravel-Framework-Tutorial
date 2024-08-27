<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(8);
        // dd($posts);

        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Add logic for creating a new post if needed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'image' => ['nullable', 'file', 'max:20000', 'mimes:png,jpg, webp'],
        ]);

         // Store image if exist.
         $path = null;
         if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('posts_images', $request->image);
         }
         

        // Create a Post
        Post::create([
            'users_id' => Auth::id(),
            'title' => $request['title'],
            'body' => $request['body'],
            'image' => $path,
        ]);

        // Redirect back with a success message
        return back()->with('success', 'Your post was created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('modify', $post);
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Authorized the action
        Gate::authorize('modify', $post);

        // Validate the request data
        $request->validate([
            'title' => ['required', 'max:255'],
            'body' => ['required'],
            'image' => ['nullable', 'file', 'max:20000', 'mimes:png,jpg, webp'],
        ]);

           // Store image if exist.
           $path = $post->image;
           if ($request->hasFile('image')) {
            if($post->image) {
                Storage::disk('public')->delete($post->image);
            }
              $path = Storage::disk('public')->put('posts_images', $request->file('image'));
           }
           

        // Update the Post
        $post->update([
            'title' => $request['title'],
            'body' => $request['body'],
            'image' => $path,
        ]);

        // Redirect to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Your post was updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Authorized the action
         Gate::authorize('modify', $post);

        //  Delete image if exist
        if($post->image) {
            Storage::disk('public')->delete($post->image);
        }


        // Delete the post
        $post->delete();

        // Redirect back to the dashboard
        return back()->with('delete', 'Your post was deleted!');
    }
}
