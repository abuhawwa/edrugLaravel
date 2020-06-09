<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
    {
        $passwordReset = DB::table('password_resets')
            ->where('token',  crypt($request->token, 'app_e_drug'))->first();

        if (!$passwordReset)
            return response()->json(['error' => 'Invalid SMS Code!'], 422);

        $user = User::where('mobile', $passwordReset->mobile)->first();

        if (!$user)
            return response()->json(['error' => "We can't find a user with that mobile number."], 422);

        Log::info($user->mobile . ' password reseted');
        $user->password = Hash::make($request->password);
        $user->save();
        DB::table('password_resets')->where('mobile', substr($user->mobile, 2, 10))->delete();
        return response()->json(['message' => 'Password Successfully Reseted'], 200);
    }
}
