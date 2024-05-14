<?php

namespace App\Http\Requests\login;

use App\Rules\PhoneNumber\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email'=>['required','email','exists:users,email'],
            'phone_number'=>['required','string',new PhoneNumberRule()],
            'password'=>['required','string','min:8','max:255'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'please enter your email',
            'email.email' => 'the email is invalid',
            'email.exists' => 'email does not match the record',
            'phone_number.required' => 'please enter your phone number',
            'phone_number.string' => 'phone number must contains characters only',
            'password.required' => 'Please enter your password.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.'
        ];

    }
}
