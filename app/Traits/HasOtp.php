<?php

namespace App\Traits;

use App\Models\Otp;
use App\Notifications\Registered;

trait HasOtp
{
    /*
    * Generate SMS OTP
    */
    public function getOtp()
    {
        return rand(1000, 9999);
    }

    /* 
    * Create new Otp Model
    */
    public function sendRegisterOtp($user)
    {
        $smsOtp = $this->getOtp();

        Otp::create([
            'user_id' => $user->id,
            'code' => $smsOtp
        ]);

        return $this->notifyUser($user, $smsOtp);
    }

    /* 
    * Send OTP to mobile via notification channel with Queue job
    */
    public function notifyUser($user, $smsOtp)
    {
        $user->notify((new Registered($user, "Verify your a/c with $smsOtp. Don't share with anyone!"))->delay(now()->addSeconds(15)));
        return response()->json(['message' => 'An OTP sent to your mobile *******' . substr($user->mobile, 7)], 201);
    }
}
