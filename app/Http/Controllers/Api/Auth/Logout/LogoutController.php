<?php

namespace App\Http\Controllers\Api\Auth\Logout;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\Logout\LogoutService;

class LogoutController extends Controller
{
    protected $registerService;
    public function __construct(LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
    }
    /************************************************************************/
    public function logout(Request $request)
    {
        try {
            $refreshToken = $this->logoutService->logout();
            return response()->json(['message' => 'Logged out successfully', 'refresh_token' => $refreshToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
