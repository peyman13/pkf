<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;
use App\Events\OTPSender;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request['username'])->first();

        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            // OTPSender::dispatch();

            return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
        } else {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users',
                'mobile' => 'required|string|min:11',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $user = User::create([
                'username' => $request->username,
                // 'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            // OTPSender::dispatch();
            return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
        }

        

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    // method for user logout and delete token
    public function otp(Request $request)
    {
        $request->otp;
        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
    
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
