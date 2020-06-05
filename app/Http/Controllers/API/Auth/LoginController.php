<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('mobile', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->mobile_verified_at) {
                return response()->json(['success' => 'Login successful'], 200);
            } else {
                Auth::logout();
                return response()->json(['message' => 'Kindly verify your mobile number first'], 403);
            }
        } else {
            return response()->json(["error" => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['success' => 'Successfully logged out'], 200);
    }
}
