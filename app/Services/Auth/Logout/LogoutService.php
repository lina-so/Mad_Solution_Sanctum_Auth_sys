<?php
namespace App\Services\Auth\Logout;

use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\userNotFound\UserNotFoundException;


Class LogoutService
{
    // use ApiResponseTrait;

    public function logout()
    {
       $user =  Auth::user();
       $user->tokens()->delete();
       return $user;
    }
}
