<?php

namespace App\Http\Controllers\Auth\Otp;

use App\Http\Controllers\Controller;
use Google2FA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function __construct()
    {
    	$this->middleware(['auth:api']);
    }

    public function index(Request $request)
    {
    	$user = $request->user();

    	$user->update([
    		'google2fa_secret' => $secret = Google2FA::generateSecretKey(),
    	]);

    	return response (
    		Google2FA::getQRCodeInline(
    			'codetube',
    			$user->email,
    			$user->google2fa_secret
    		)
    	);
    }


    public function store(Request $request)
    {
    	$this->validate($request, [
    		'otp' => 'required'
    	]);

    	$user = $request->user();

    	if (!Google2FA::verifyKey($user->google2fa_secret, $request->otp)) {
    		return response(null, 401);
    	}

    	$user->update([
    		'google2fa_enabled' => 1,
    	]);
    }

    public function destroy(Request $request)
    {
    	$user = $request->user();

    	$this->validate($request, [
    		'password' => [
    			'required',
    			function ($attribute, $value, $fail) use ($user) {
    				if (!Hash::check($value, $user->password)) {
    					$fail('Incorrect password');
    				}
    			}
    		]
    	]);

    	$user->update([
    		'google2fa_secret' => null,
    		'google2fa_enabled' => 0
    	]);
    }
}
