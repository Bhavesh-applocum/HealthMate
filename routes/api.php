<?php

use App\Client;
use App\Http\Controllers\AddressController;
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
Route::get('/candidate/{id}/index', 'CandidateController@show');
Route::post('/client/register', 'ClientController@store');
Route::get('/client/{id}/index', 'ClientController@show');
Route::post('/login', 'LoginController@login');
Route::post('/otpVerify', 'LoginController@verifyotp'); // login otp
Route::post('/resendOtp', 'LoginController@resendotp'); // resend login otp
Route::post('/forgotPassword', 'LoginController@forgotPassword'); 
Route::post('/verifyforgototp','LoginController@verifyforgototp'); // forgot password otp
Route::post('/resendforgotpasswordotp', 'LoginController@resendforgotpasswordotp'); //resend forgot password otp
Route::post('/resetPassword', 'LoginController@resetpassword'); 
Route::post('/candidate/editprofile', 'CandidateController@profileedit');
Route::post('/candidate/upload/image', 'CandidateController@uploadImage');
Route::post('/client/editprofile', 'ClientController@profileedit');

Route::resource('job', JobController::class);
Route::get('/job/index/all', 'JobController@findJobs');
Route::resource('timesheet', TimesheetController::class);
Route::get('/address/{id}/index', 'AddressController@index');
Route::post('/address/store', 'AddressController@store');
Route::post('/setas/default', 'AddressController@isDefault');
Route::post('/address/update', 'AddressController@update');
Route::delete('/address/{id}/delete', 'AddressController@destroy');
Route::get('/edit/{id}/address', 'AddressController@edit');

Route::resource('application', CandidateApplicationController::class);
Route::resource('client/application', ClientApplicationController::class);
Route::post('/application/approve', 'ClientApplicationController@approveApplication');
Route::post('/application/reject', 'ClientApplicationController@afterApplicatonRejected');
Route::post('/application/client/index', 'ClientApplicationController@statusforclient');
Route::post('/application/status/jobs' , 'CandidateApplicationController@showstautsjob');
Route::get('/booking/{id}/index', 'ClientApplicationController@BookingCandidate');
Route::post('/job/update/{id}', 'JobController@jobupdate');
Route::get('/job/index/{id}/client', 'JobController@clientJobs');
Route::get('/job/{id}/specific/candidate', 'JobController@specificJob');
Route::get('/job/{id}/specific/candidate/dashboard', 'JobController@candidateDashboard');
Route::get('/check/timesheet/{id}','CandidateApplicationController@genaratetimesheet');
Route::post('/timesheet/approve','ClientApplicationController@approveTimesheet');

Route::post('/get/all/address', 'ClientController@getAddress');
Route::get('/hello', function () {
    return 'Hello World';
});
