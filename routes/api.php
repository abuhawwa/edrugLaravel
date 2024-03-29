<?php

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

// Auth Routes...
Route::post('login', 'API\Auth\LoginController@login');
Route::post('register', 'API\Auth\RegisterController@register');
Route::post('sms/verify', 'API\Auth\VerificationController@verify');
Route::post('sms/resend', 'API\Auth\VerificationController@resend');
Route::post('password/sms', 'API\Auth\ForgotPasswordController@sendResetOtpSms');
Route::post('password/reset', 'API\Auth\ResetPasswordController@reset');
Route::post('logout', 'API\Auth\LoginController@logout');
