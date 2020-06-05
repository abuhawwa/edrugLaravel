<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HasOtp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use HasOtp;

    public function sendResetOtpSms(Request $request)
    {
        $request->validate(['mobile' => 'required|numeric|regex:/^[0-9]{10}$/']);

        // find the user from user model, if not found throw exception  
        try {
            $user = User::where('mobile', $request->mobile)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User with mobile number not found!'], 422);
        }
        return $this->sendPasswordOtp($user);
    }
}
