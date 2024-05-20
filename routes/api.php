<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;


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



$api_path = '/Api/';
Route::prefix('api')->group(function() use ($api_path){

    include __DIR__ . "{$api_path}file.php";
    include __DIR__ . "{$api_path}auth.php";

});



// Route::post('/login',[AuthController::class,'login'])->name('login');

// Route::post('/register',[AuthController::class,'signUp'])->name('register');

Route::middleware('auth:sanctum')->group(function () {

    // Route::post('/logout/{id}',[AuthController::class,'logout'])->name('logout');
    // Route::post('/resend-code',[AuthController::class,'resendVerifyCode'])->name('resendVerifyCode');
    // Route::post('/confirm-code',[AuthController::class,'confirmVerifyCode'])->name('confirmVerifyCode');
    // Route::post('/refresh-token',[AuthController::class,'refreshToken'])->name('refreshToken');


});
