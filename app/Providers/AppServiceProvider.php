<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Rules\PhoneNumber\PhoneNumberRule;
use Illuminate\Support\Facades\Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
        //     $result = (new PhoneNumberRule())->validate($attribute, $value, function ($message) use ($validator) {
        //         // If validation fails, add an error message to the validator
        //         $validator->errors()->add('phone_number', $message);
        //     });

        //     // Return the result of the validation
        //     return $result;
        // });
    }
}
