<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google2FA;

class SignInController extends Controller
{
    public function __invoke(Request $request)
    {
    	if (!$token = auth()->attempt($request->only('email', 'password'))) {
    		return response([
    			'reason' => 'Incorrect Credentials'
    		], 401);
    	}

    	$user = $request->user();

    	if ($user->google2fa_enabled && !$request->otp) {
    		return response([
    			'reason' => 'Requires Otp'
    		], 401);
    	}

    	if ($user->google2fa_enabled && $request->otp) {
    		if (!Google2FA::verifyKey($user->google2fa_secret, $request->otp)) {
    			return response([
    				'reason' => 'Invalid Otp'
    			], 401);
    		}
    	}

    	return response()->json(compact('token'));
    }
}
