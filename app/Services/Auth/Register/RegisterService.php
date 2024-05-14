<?php
namespace App\Services\Auth\Register;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\VereficationCodeEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;

Class RegisterService
{
    public function signUp($request)
    {
        DB::beginTransaction();
        try{
            $requestData = $request->validated();

            if($request->hasFile('profile_photo'))
            {
                $imagePath = $this->UploadFile('image',$request->file('profile_photo') , $requestData['user_name']);
            }

            if($request->hasFile('certificate'))
            {
                $filePath = $this->UploadFile('pdf',$request->file('certificate') , $requestData['user_name']);
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

            $token = $user->createToken('authToken')->plainTextToken;

            return ['token' => $token];

        }catch(\Exception $e)
        {
            DB::rollback();
            return $e->getMessage();
        }



    }
/************************************************************************************/
    public function UploadFile($file_type,$image , $user_name)
    {
        if($file_type=='image')
        {
            $folderName = 'profile';
            $folderPath = 'images/' . $folderName . '/users/' . $user_name ;
            $path = Storage::disk('public')->putFile($folderPath, $image);

        }
        else if($file_type=='pdf')
        {
            $folderName = 'pdf';
            $folderPath = 'Files/' . $folderName . '/users/' . $user_name ;
            $path = Storage::disk('public')->putFile($folderPath, $image);

        }

        return $path;
    }

    /************************************************************************************/
    public function resendVerifyCode()
    {
        $user = auth()->user();
        $user->generateVerificationCode();
        return $user->verify_code;

    }
    /***************************************************************************************/
    public function confirmVerifyCode($request)
    {

        $user = Auth::user();
        $code = $request->verify_code;
        $expired_at = $user->created_at->addMinutes(3);

        if ($code !== $user->verify_code || now() > $expired_at)
        {
            return false;
        }else{
            $user->resetVerificationCode();
            $user->email_verified_at = now();
            $user->save();
            return true;
        }

    }

}
