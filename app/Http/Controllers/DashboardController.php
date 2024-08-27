<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class DashboardController extends Controller 
{
    
    public function index() {

        $posts = Post::where('users_id', Auth::id())->latest()->paginate(6);
        

        // dd($posts);

        return  view('users.dashboard', [ 'posts' => $posts]);
    }

    public function userPosts(User $user) {
        // dd($user->posts);
        $userPosts = $user->posts()->latest()->paginate(6);
        return view('users.posts', ['posts' => $userPosts, 'user' => $user ]);
    }
}
