<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;

Route::controller(AuthController::class)
    ->prefix('auth')
    ->group(function(){

        Route::middleware('auth:sanctum')
        ->group(function(){
            Route::delete('delete-file','deleteFile')->name('file.delete');
        });

    });
