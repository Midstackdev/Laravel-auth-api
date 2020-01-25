<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SignUpController extends Controller
{
    public function __invoke(SignUpFormRequest $request)
    {
    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password),
    	]);

    	return $user;
    }
}
