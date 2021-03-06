<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Events\OTPSender;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request['username'])->first();

        if ($user) {
            OTPSender::dispatch($user);

            return response()->json(['status' => 1], 200)->header('Content-Type', 'application/json');
        } else {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users',
                'mobile' => 'required|string|min:11',
                'captcha' => 'required|captcha_api:'. request('key') . ',math'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $user = User::create([
                'username' => $request->username,
                'mobile' => $request->mobile,
            ]);
            OTPSender::dispatch($user);

            return response()->json(['status' => 1], 200)->header('Content-Type', 'application/json');
        }
    }

    // method for user logout and delete token
    public function otp(Request $request)
    {
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401)
                ->header('Content-Type', 'application/json');
        }

        $user = User::where('username', $request['username'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    // method for refresh otp
    public function otpRefresh(Request $request)
    {
        $user = User::where('username', $request['username'])->first();
        if ($user) {
            OTPSender::dispatch($user);

            return response()->json(['status' => 1], 200)->header('Content-Type', 'application/json');
        }

        return response()->json(['status' => -1], 301)
            ->header('Content-Type', 'application/json');
    }

    // method for refresh logout
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
