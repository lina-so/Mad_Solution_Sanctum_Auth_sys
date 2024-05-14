<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\Login\LoginController;
use App\Http\Controllers\Api\Auth\Logout\LogoutController;
use App\Http\Controllers\Api\Auth\Register\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('/login',[LoginController::class,'login'])->name('login')->middleware('twoFactor');
Route::post('/register',[RegisterController::class,'signUp'])->name('register');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout/{id}',[LogoutController::class,'logout'])->name('logout');
    Route::post('/resend-code',[RegisterController::class,'resendVerifyCode'])->name('resendVerifyCode');
    Route::post('/confirm-code',[RegisterController::class,'confirmVerifyCode'])->name('confirmVerifyCode');
    Route::post('/refresh-token',[RegisterController::class,'refreshToken'])->name('refreshToken');


});
