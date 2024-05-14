<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'phone_number',
        'profile_photo',
        'certificate',
        'verify_code',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public  function generateVerificationCode()
    {
        $verificationCode = $this->generateCode();
        // $this->timestamps = false;
        $this->verify_code = $verificationCode;
        $this->email_verified_at = null;
        $this->save();
    }

    /***********************************************/
    public function generateCode()
    {
        $characters = '0123456789ABCDEYZab0123456789cdefghijk0123456789';
        $verificationCode = '';
        for ($i = 0; $i < 6; $i++) {
            $verificationCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $verificationCode;
    }

    /***********************************************/

    public  function resetVerificationCode()
    {
        $this->verify_code = null;
        $this->save();
    }
}
