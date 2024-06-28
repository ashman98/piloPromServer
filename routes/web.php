<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

//Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);
//
//Route::get('/about',[\App\Http\Controllers\AboutController::class, 'index']);
//
//Route::get('/about/{id}',[\App\Http\Controllers\AboutController::class, 'user']);

//Route::post('graphql', [GraphQLController::class, 'query']);

//Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');

//Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//    $request->fulfill();
//
//    return redirect('/home');
//})->middleware(['auth', 'signed'])->name('verification.verify');
//
//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
