<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function() {
    Route::post('signin', 'SignInController');
    Route::post('signup', 'SignUpController');
    Route::post('signout', 'SignOutController');

    Route::get('me', 'MeController');

    Route::get('otp', 'Otp\OtpController@index');
    Route::post('otp', 'Otp\OtpController@store');
    Route::delete('otp', 'Otp\OtpController@destroy');
});