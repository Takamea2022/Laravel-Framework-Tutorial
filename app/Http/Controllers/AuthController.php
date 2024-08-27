<?php

namespace App\Http\Controllers;
use App\Models\User;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //  Register User
    public function register(Request $request) {
        // dd($request->username); 

        //  Validate
        $fields =  $request->validate([
            'username' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'min:3', 'confirmed'],
        ]);
        // dd($fields);
        // Register
        $user =  User::create($fields);
         

        // Login
        Auth::login($user);
 
        //  Redirect
        return redirect()->route('home');
    } 

    // Login User
    public function login(Request $request) {
       //  Validate
       $fields =  $request->validate([
      
        'email' => ['required', 'max:255', 'email'],
        'password' => ['required'],
    ]);
        // dd($request);
        // Try to logged in
        
        if(Auth::attempt($fields, $request->remember)) {
            return redirect()->intended('dashboard');
        }
        else {
            return back()->withErrors([
                'failed' => 'The provided credentials do not match our records.'
            ]);
        }
    }
    // Logout User
    public function logout(Request $request) {
        // dd('ok');

        // Logout the user
        Auth::logout();

        // Invalidate user's session
        $request->session()->invalidate();

        //  Redirect to home
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
