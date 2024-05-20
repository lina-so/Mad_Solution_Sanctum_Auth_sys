<?php
namespace App\Services\Auth\Register;

use App\Models\User;
use Illuminate\Http\Response;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\VereficationCodeEvent;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Traits\Files\FileOperationsTrait;
use App\Exceptions\Otp\InvalidCodeException;
use App\Exceptions\userNotFound\UserNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Class RegisterService
{
    use FileOperationsTrait ,ApiResponseTrait;

    public function signUp($request)
    {
        DB::beginTransaction();
        try{
            $requestData = $request->validated();

            if($request->hasFile('profile_photo'))
            {
                $file =$request->file('profile_photo') ;
                $imagePath = $this->generatePath('image',$file, $requestData['user_name']);
                $this->uploadFile($file, $imagePath);

            }

            if($request->hasFile('certificate'))
            {
                $file =$request->file('certificate') ;
                $filePath = $this->generatePath('pdf',$file, $requestData['user_name']);
                $this->uploadFile($file, $filePath);

            }

            $user = User::create([
                'user_name' => $requestData['user_name'],
                'email' => $requestData['email'],
                'password' => Hash::make($requestData['password']),
                'phone_number'=>$requestData['phone_number'],
                'profile_photo'=>$imagePath ?? null,
                'certificate'=>$filePath ?? null,
            ]);

            // verification Event
            event(new VereficationCodeEvent($user));

            DB::commit();

            $accessToken = $user->createToken('#$_auth_token_@#',
             ['expires_in' => config('sanctum.expiration')])->plainTextToken;


            return ['token'=> $accessToken];


        }catch(Throwable $exception)
        {
            DB::rollback();
            return $this->handleException($exception);

            // return $e->getMessage();
        }



    }
/************************************************************************************/
    public function generatePath($file_type,$image , $user_name)
    {
        if($file_type=='image')
        {
            $folderName = 'profile';
            $folderPath = 'images/' . $folderName . '/users/' . $user_name ;
            // $path = Storage::disk('public')->putFile($folderPath, $image);

        }
        else if($file_type=='pdf')
        {
            $folderName = 'pdf';
            $folderPath = 'Files/' . $folderName . '/users/' . $user_name ;
            // $path = Storage::disk('public')->putFile($folderPath, $image);

        }

        return $folderPath;
    }
    /************************************************************************************/
    public function resendVerifyCode()
    {
        $user = auth()->user();
        if(!$user)
        {
            throw new AuthenticationException();
        }
        $user->generateVerificationCode();
        return $user->verify_code;

    }
    /***************************************************************************************/
    public function confirmVerifyCode($request)
    {
        $user = Auth::user();
        if(!$user)
        {
            throw new AuthenticationException();
        }

        $code = $request->verify_code;
        $expired_at = $user->created_at->addMinutes(3);

        if ($code !== $user->verify_code || now() > $expired_at)
        {
            throw new InvalidCodeException();
        }else{
            $user->resetVerificationCode();
            $user->email_verified_at = now();
            $user->save();
            return true;
        }

    }

    /*********************************************************************************************/
    public function refreshToken()
    {
        $user = Auth::user();
        if(!$user)
        {
            throw new AuthenticationException();
        }
        // $oldToken = $user->currentAccessToken()->delete();
        $user->tokens()->delete();
        $refreshToken = $user->createToken('#$_refresh_token_@#',
        ['expires_in' => config('sanctum.rt_expiration')])->plainTextToken;
        return $refreshToken;
    }
}
