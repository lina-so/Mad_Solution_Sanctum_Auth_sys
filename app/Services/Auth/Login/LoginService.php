<?php
namespace App\Services\Auth\Login;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

Class LoginService
{

    public function login($request)
    {
        $requestData = $request->validated();
        if (Auth::attempt($requestData)) {
            $user = User::whereEmail($requestData['email'])->first();
            Auth::login($user);
            $authUser = Auth::user();
            $accessToken = $authUser->createToken('#$_my_app_token_@#',
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
