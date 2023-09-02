<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AssignmentController;



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



Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');


Route::post('/register', [AuthController::class, 'register']);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');


Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::post('/post-job', [ClientController::class, 'postJob'])->name('submit-job');


Route::middleware(['auth'])->group(function () {

    Route::get('/profile', 'App\Http\Controllers\ProfileController@index')->name('profile');
    Route::post('/profile', 'App\Http\Controllers\ProfileController@update')->name('profile.update');


    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');
    Route::post('/post-job', [ClientController::class, 'postJob'])->name('postJob');

    Route::get('/job-details/{id}', [ClientController::class, 'viewJobDetails'])->name('job-details');

    Route::post('/updateJob/{job}', [ClientController::class, 'updateJob'])->name('updateJob');

});









// Navigation Routes
Route::get('/client', [ClientController::class, 'index'])->name('client_mode');

Route::get('employee', [EmployeeController::class, 'index'])->name('employee_mode');

Route::post('/employee/search', [EmployeeController::class, 'search'])->name('searchJob');

Route::get('/employee/job-fll-view/{id}', [EmployeeController::class, 'jobFullView'])->name('jobFullView');

Route::post('/applications/accept/{userId}/{jobId}', [ApplicationController::class, 'acceptApplication'])->name('applications.accept');

Route::post('/applications/cancel', [ApplicationController::class, 'cancelApplication'])->name('applications.cancel');

Route::post('/client/deleteJob/{jobId}', [ClientController::class, 'deleteJob'])->name('client.deleteJob');

Route::get('/generalProfile/{id}', [ProfileController::class, 'generalProfile'])->name('generalProfile');

Route::post('/assignments/create/{applicationId}', [AssignmentController::class, 'createAssignment'])->name('assignments.create');






