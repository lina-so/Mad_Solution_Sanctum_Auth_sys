<?php
namespace App\Services\Auth\Logout;

use Illuminate\Support\Facades\Auth;


Class LogoutService
{
    public function logout()
    {
        Auth::user()->tokens()->delete();
        // Generate a new refresh token
        $refreshToken = Auth::user()->createToken('authRefreshToken')->plainTextToken;
        return $refreshToken;
    }
}
