<?php

namespace App\Http\Requests\Register;

use App\Rules\PhoneNumber\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'=>['required','email'],
            'phone_number'=>['required','string',new PhoneNumberRule()], // 963 985537632 or 20 5489963214
            'user_name'=>['required','string','min:3','max:255'],
            'profile_photo'=>['nullable','image','mimes:png,jpg','dimensions:min_width=200,min_height=200,max_width=1000,max_height=1000'],
            'certificate'=>['nullable','file','mimes:pdf','max:524288000'], //max:524288000 // 500 * 1024 * 1024 = 524288000 بايت (500 ميغابايت)
            'password'=>['required','string','min:8','confirmed']

        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'please enter your email',
            'email.email' => 'the email is invalid',
            'phone_number.required' => 'please enter your phone number',
            'phone_number.string' => 'phone number must contains characters only',
            'user_name.required' => 'please enter the username',
            'user_name.string' => 'username must contains characters only',
            'user_name.min' => 'username must be at least 3 characters',
            'user_name.max' => 'username must be less than 255 characters.',
            'profile_photo.image' => 'The profile photo must be an image.',
            'profile_photo.mimes' => 'Supported image formats just: png, jpg.',
            'profile_photo.dimensions' => 'Profile photo dimensions should be between 200x200 and 1000x1000 pixels.',
            'certificate.file' => 'The certificate must be a file.',
            'certificate.mimes' => 'Supported file formats: pdf.',
            'certificate.max' => 'The file size must not exceed 500 megabytes.',
            'password.required' => 'Please enter your password.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.'
        ];

    }
}
