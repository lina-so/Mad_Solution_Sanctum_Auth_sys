<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function(){

        Route::middleware('auth:sanctum')
        ->group(function(){
            Route::post('/logout/{id}','logout')->name('logout');
            Route::post('/confirm-code','confirmVerifyCode')->name('confirmVerifyCode');
            Route::post('/refresh-token','refreshToken')->name('refreshToken');
        });

        Route::middleware('guest:sanctum')
        ->group(function(){
            Route::post('/login','login')->name('login');
            Route::post('/register','signUp')->name('register');
            Route::post('/resend-code','resendVerifyCode')->name('resendVerifyCode')->middleware('throttle:3,1');

        });

    });
