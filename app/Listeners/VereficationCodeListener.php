<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\VereficationCodeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Session;
use App\Notifications\Virify\VereficationCodeNotification;

class VereficationCodeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VereficationCodeEvent $event): void
    {
        $user = $event->data;
        $email = $user->email;

        $verificationCode = $user->generateVerificationCode();
        // session()->put('verify_code', $verificationCode);
        // $verificationCode = session('verify_code');

        $expired_at = $user->created_at->addMinutes(3);
        $minutesRemaining = now()->diffInMinutes($expired_at);
        $user->notify(new VereficationCodeNotification($verificationCode,$minutesRemaining));

    }
}
