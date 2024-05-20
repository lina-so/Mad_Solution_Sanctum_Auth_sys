<?php
namespace App\Services\Auth\Login;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\userNotFound\UserNotFoundException;


Class LoginService
{
    use ApiResponseTrait;

    public function login($request)
    {
        $requestData = $request->validated();
        $user = User::whereEmail($requestData['email'])->first();

        if (!$user) {

            throw ValidationException::withMessages([
                'email' => 'The provided email  does not match our records.'
            ]);
        }

        if (!Hash::check($requestData['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'The provided password does not match our records.'
            ]);
        }
        // Attempt to log in the user
        if (!Auth::attempt($requestData)) {
            return response()->json([
                'message' => 'Your token has expired, please login again'
            ], 401);
        }

        Auth::login($user);
        $authUser = Auth::user();

        if ($authUser->verify_code !== null) {
            return response()->json([
                'message' => 'Please verify your email'
            ], 401);
        }

        $accessToken = $authUser->createToken('#$_my_app_token_@#', ['expires_in' => config('sanctum.expiration')])->plainTextToken;

         return response()->json(['access_token' => $accessToken], 200);



    }

}
