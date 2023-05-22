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
        Route::get('client', 'ClientController@index')->name('clients.index');
        Route::get('client/view/{id}','ClientController@show')->name('clients.show');
        Route::delete('client/delete/{id}','ClientController@destroy')->name('clients.delete');
        Route::get('client/edit/{id}', 'ClientController@edit')->name('clients.edit');
        Route::put('client/update/{id}', 'ClientController@update')->name('clients.update');
        Route::get('create/client', 'ClientController@create')->name('clients.create');
        Route::post('store/client', 'ClientController@store')->name('clients.store');
        Route::get('candidate', 'CandidateController@index')->name('candidates.index');
        Route::get('candidate/view/{id}','CandidateController@show')->name('candidates.show');
        Route::delete('candidate/delete/{id}','CandidateController@destroy')->name('candidates.delete');
        Route::get('candidate/edit/{id}', 'CandidateController@edit')->name('candidates.edit');
        Route::put('candidate/update/{id}', 'CandidateController@update')->name('candidates.update');
        Route::get('create/candidate', 'CandidateController@create')->name('candidates.create');
        Route::post('store/candidate', 'CandidateController@store')->name('candidates.store');
        Route::get('jobs', 'JobController@index')->name('jobs.index');
        Route::delete('delete/job/{id}', 'JobController@destroy')->name('jobs.delete');
        Route::get('/job/view/{id}','JobController@show')->name('jobs.show');
        Route::get('job/edit/{id}', 'JobController@edit')->name('jobs.edit');
        Route::get('/address/{id}', 'JobController@editAjaxArea')->name('jobs.area.edit');
        Route::put('job/update/{id}', 'JobController@update')->name('jobs.update');
        Route::get('create/job', 'JobController@create')->name('jobs.create');
        Route::get('/getDashboardInfo', 'AdminController@getDashboardInfo')->name('getDashboardInfo');
        Route::get('/dashboardTimesheetInfo', 'AdminController@getTimesheetChartInfo')->name('getTimesheetChartInfo');
        Route::get('/dashboardCategoryInfo', 'AdminController@getCategoryWiseCandidateAndCreatedJob')->name('getCategoryInfo');
    });
});




// Route::get('/resetpassword', 'LoginController@forgotpasswordLoad');
// Route::post('/resetpassword', 'LoginController@resetpassword');

