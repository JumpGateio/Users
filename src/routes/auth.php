<?php

// Authentication
Route::group(['middleware' => 'guest', 'namespace' => 'JumpGate\Users\Http\Controllers'], function () {
    if (config('jumpgate.users.enable_social') == false) {
        Route::get('login', [
            'as'   => 'auth.login',
            'uses' => 'AuthController@login',
        ]);
        Route::post('login', [
            'as'   => 'auth.login',
            'uses' => 'AuthController@handleLogin',
        ]);

        Route::get('register', [
            'as'   => 'auth.register',
            'uses' => 'AuthController@register',
        ]);
        Route::post('register', [
            'as'   => 'auth.register',
            'uses' => 'AuthController@handleRegister',
        ]);

        Route::get('forgot-password', [
            'as'   => 'auth.forgot-password',
            'uses' => 'AuthController@forgotPassword',
        ]);
        Route::post('forgot-password', [
            'as'   => 'auth.forgot-password',
            'uses' => 'AuthController@handleForgotPassword',
        ]);
    } else {
        Route::get('login/{provider?}', [
            'as'   => 'auth.login',
            'uses' => 'SocialAuthController@login',
        ]);
        Route::get('callback/{provider}', [
            'as'   => 'auth.callback',
            'uses' => 'SocialAuthController@callback',
        ]);
    }
});

Route::group(['middleware' => ['auth'], 'namespace' => 'JumpGate\Users\Http\Controllers'], function () {
    if (config('jumpgate.users.enable_social') == false) {
        Route::get('logout', [
            'as'   => 'auth.logout',
            'uses' => 'AuthController@logout',
        ]);
    } else {
        Route::get('logout', [
            'as'   => 'auth.logout',
            'uses' => 'SocialAuthController@logout',
        ]);
    }
});
