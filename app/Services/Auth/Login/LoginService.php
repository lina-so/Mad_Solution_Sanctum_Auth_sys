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

        if (!Hash::check($requestData['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'The provided password does not match our records.'
            ]);
        }

        Auth::login($user);
        $authUser = Auth::user();

        if ($authUser->verify_code !== null) {
            $message = "Please verify your email";
            return $this->apiError(message: $message,code: 401);
        }

        $accessToken = $authUser->createToken('#$_my_app_token_@#', ['expires_in' => config('sanctum.expiration')])->plainTextToken;

         return response()->json(['access_token' => $accessToken], 200);



    }

}
