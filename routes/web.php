<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Display the registration form
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');

// Handle the registration form submission
Route::post('/register', [AuthController::class, 'register']);

// Display the login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle the login form submission
Route::post('/login', [AuthController::class, 'login']);

//Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');




Route::middleware(['auth'])->group(function () {
    // Display and update the user's profile data
    Route::get('/profile', 'App\Http\Controllers\ProfileController@index')->name('profile');
    Route::post('/profile', 'App\Http\Controllers\ProfileController@update')->name('profile.update');

    //Route to change password
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
});




