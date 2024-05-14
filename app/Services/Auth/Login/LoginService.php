<?php
namespace App\Services\Auth\Login;

use Illuminate\Support\Facades\Auth;

Class LoginService
{

    public function login($request)
    {
        $requestData = $request->validated();
        if (Auth::attempt($requestData)) {
            $user = Auth::user();

            $accessToken = $user->createToken('#$_my_app_token_@#',
             ['expires_in' => config('sanctum.expiration')])->plainTextToken;

            return $accessToken;
        } else {
            $errorResponse = [
                'message' => 'Invalid credentials',
            ];
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json($errorResponse, 422));
        }
    }
}
