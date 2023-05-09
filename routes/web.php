<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');
//guest middleware
Route::group([
    'middleware' => ['guest', 'prevent-back-history'],
], function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login_form');
    Route::post('login', 'LoginController@login')->name('login');
    // Route::get('register', 'RegisterController@showRegisterForm')->name('register_form');
    // Route::post('register', 'RegisterController@register')->name('register');
    Route::get('otpverification/{id}', 'OtpController@showOtpVerificationForm')->name('otp_verification_form');
    Route::get('hello', 'OtpController@hello')->name('hello');
    Route::post('verify-otp', 'OtpController@verifyOtp')->name('verify-otp');
    Route::get('resend-otp/{id}', 'OtpController@resendOtp')->name('resend-otp');
});

//guest middleware
Route::get('/logout', 'LoginController@logout')->name('logout');
//admin middleware
Route::group([
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['admin.auth', 'prevent-back-history'],
], function () {
    Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
    Route::group([
        'prefix' => 'admin',
    ], function () {
        Route::get('/', 'AdminController@index')->name('home'); // admin home
        Route::get('/getDashboardInfo', 'AdminController@getDashboardInfo')->name('getDashboardInfo');
    });
});




// Route::get('/resetpassword', 'LoginController@forgotpasswordLoad');
// Route::post('/resetpassword', 'LoginController@resetpassword');

