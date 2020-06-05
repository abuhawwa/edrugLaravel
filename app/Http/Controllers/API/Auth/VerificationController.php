<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationRequest;
use App\Models\Otp;
use App\Models\User;
use App\Traits\HasOtp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use HasOtp;

    public $error = 'Invalid SMS OTP or Expired!';

    /* 
    * Verify Registered user mobile number with valid sms otp
    */
    public function verify(VerificationRequest $request)
    {
        // find the user otp code from otp model, if not found throw exception  
        try {
            $otp = Otp::where('code', crypt($request->code, 'app_e_drug'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => ["code" => [$this->error]]], 422);
        }

        // if above all true, check wheather user entered used otp code or expired otp code
        if (!$otp->isValid())
            return response()->json(['error' => $this->error], 422);

        $otp->used = true; //set true for current used otp
        $otp->save();
        $user = $otp->user;
        $user->mobile_verified_at = date('Y-m-d H:i:s'); // set datetime for user, when the code is verified
        $user->save();
        return response()->json(['message' => 'Thank You, your mobile number is verified'], 200);
    }

    /* 
    * resend a new sms otp to registered user mobile number
    */
    public function resend(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric|regex:/^[0-9]{10}$/',
        ]);

        // find the user from user model, if not found throw exception  
        try {
            $user = User::where('mobile', $request->mobile)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(["errors" => ["code" => ['User with mobile number not found!']]], 422);
        }

        // check user already verified
        if ($user->isVerified())
            return response()->json(['error' => 'You are already verified!'], 422);
        else
            return $this->sendRegisterOtp($user);
    }
}
