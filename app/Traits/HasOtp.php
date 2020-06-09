<?php

namespace App\Traits;

use App\Models\Otp;
use App\Notifications\Registered;
use Illuminate\Support\Facades\DB;

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

    /**
     * 
     * Create new password_resets for the user
     */
    public function sendPasswordOtp($user)
    {
        $smsOtp = $this->getOtp();

        DB::table('password_resets')->insert([
            'mobile' => substr($user->mobile, 2, 10),
            'token' => crypt($smsOtp, 'app_e_drug'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->notifyUser($user, $smsOtp);
    }

    /* 
    * Send OTP to mobile via notification channel with Queue job
    */
    public function notifyUser($user, $smsOtp)
    {
        $user->notify((new Registered("$smsOtp. Don't share with anyone!"))->delay(now()->addSeconds(15)));
        return response()->json(['message' => 'An OTP sent to your mobile *******' . substr($user->mobile, 9)], 201);
    }
}
