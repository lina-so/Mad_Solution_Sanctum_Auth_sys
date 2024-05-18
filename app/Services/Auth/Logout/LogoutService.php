<?php
namespace App\Services\Auth\Logout;

use Illuminate\Support\Facades\Auth;


Class LogoutService
{
    public function logout()
    {
       $user =  Auth::user()->tokens()->delete();
    // Auth::logout();
       return $user;
    }
}
