<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Display the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle user registration
    public function register(Request $request)
{
    // Validate the user's input
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8',
    ]);

    // Create a new user in the database
    $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password), // Hash the password
    ]);

    // Log the user in after registration (optional)
    // Auth::login($user);

    // Redirect the user after successful registration
    return redirect('/')->with('success', 'Registration successful.');
}


    // Display the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle user login
    public function login(Request $request)
{
    // Validate the form data
    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string',
    ]);

    // Determine if the input is an email or username
    $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Define the credentials array based on the login type
    $credentials = [
        $loginType => $request->input('login'),
        'password' => $request->input('password'),
    ];

    // Attempt to authenticate the user
    if (auth()->attempt($credentials)) {
        // Authentication successful, redirect the user
        return redirect('/');
    }

    // Authentication failed, return back with errors
    return back()->withErrors(['login' => 'Invalid credentials']);
}


    public function logout()
{
    Auth::logout();

    // Redirect to the home page or any other desired page after logout
    return redirect('/')->with('success', 'You have logged out!');
}
}
