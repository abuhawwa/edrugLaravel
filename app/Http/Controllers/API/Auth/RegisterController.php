<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\HasOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use HasOtp;

    /* 
    * Register as Admin user
    * Verify mobile number with OTP
    */
    public function register(RegisterRequest $request)
    {
        $admin = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'password' => Hash::make($request->password),
            'role_id' => 1,
            'status' => 'inactive',
        ]);
        return $this->sendRegisterOtp($admin);
    }
}
