<?php

use App\Client;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/candidate/register', 'CandidateController@store');
Route::post('/client/register', 'ClientController@store');
Route::post('/login', 'LoginController@login');
Route::post('/otpVerify', 'LoginController@verifyotp'); // login otp
Route::post('/resendOtp', 'LoginController@resendotp'); // resend login otp
Route::post('/forgotPassword', 'LoginController@forgotPassword'); 
Route::post('/verifyforgototp','LoginController@verifyforgototp'); // forgot password otp
Route::post('/resendforgotpasswordotp', 'LoginController@resendforgotpasswordotp'); //resend forgot password otp
Route::post('/resetPassword', 'LoginController@resetpassword'); 
Route::post('/candidate/editprofile', 'CandidateController@profileedit');
Route::post('/client/editprofile', 'ClientController@profileedit');

Route::resource('job', JobController::class);

Route::post('/job/update/{id}', 'JobController@jobupdate');
Route::get('/job/{id}/index', 'JobController@clientJobs');

Route::get('/hello', function () {
    return 'Hello World';
});
