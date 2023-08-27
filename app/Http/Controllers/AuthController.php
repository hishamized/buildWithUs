<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function showRegistrationForm()
    {
        return view('auth.register');
    }


    public function register(Request $request)
{

    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8',
    ]);


    $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);





    return redirect('/')->with('success', 'Registration successful.');
}



    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
{

    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string',
    ]);


    $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';


    $credentials = [
        $loginType => $request->input('login'),
        'password' => $request->input('password'),
    ];


    if (auth()->attempt($credentials)) {

        return redirect('/');
    }


    return back()->withErrors(['login' => 'Invalid credentials']);
}

public function changePassword(Request $request)
{

    $userId = auth()->id();

     $user = User::find($userId);


    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8',
        'confirm_password' => 'required|min:8',
    ]);


    if ($request->new_password !== $request->confirm_password) {
        return redirect()->route('profile')->with('error', 'Please make sure you match the new passwords.');
    }






    if (!Hash::check($request->current_password, $user->password)) {
        return redirect()->route('profile')->with('error', 'Incorrect current password.');
    }


    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->route('profile')->with('success', 'Password changed successfully.');
}




    public function logout()
{
    Auth::logout();


    return redirect('/')->with('success', 'You have logged out!');
}
}
