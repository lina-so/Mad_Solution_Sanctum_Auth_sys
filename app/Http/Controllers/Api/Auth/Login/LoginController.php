<?php

namespace App\Http\Controllers\Api\Auth\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\login\LoginRequest;
use App\Services\Auth\Login\LoginService;

class LoginController extends Controller
{
    protected $loginService;
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }
    /************************************************************************/
    public function login(LoginRequest $request)
    {
        $data = $this->loginService->login($request);
        return response()->json(['Token'=>$data,'message' => 'Logged in successfully']);

    }
}
