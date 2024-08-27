<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;

// Route::get('/', function () {
//     return view('posts.index');
// })->name('home');

//Simplier version rather the top Routing code
Route::view('/', 'posts.index')->name('home');
Route::redirect('/', 'posts');

Route::get('/users/{user}/posts', [DashboardController::class, 'userPosts'])->name('posts.users');


Route::resource('posts', PostController::class);



// Route for creating the user post. 
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

// Middleware Auth group
Route::middleware('auth')->group(function () {

  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

// Middleware guest group
Route::middleware('guest')->group(function () {

  Route::view('/register', 'auth.register')->name('register'); 
  Route::post('/register', [AuthController::class, 'register']);

  Route::view('/login', 'auth.login')->name('login');
  Route::post('/login', [AuthController::class, 'login']);

});



