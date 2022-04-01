<?php

/*
|--------------------------------------------------------------------------
| Client API Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'v1', 'as' => 'v1.', 'namespace' => 'V1'], function () {

    // Public
    Route::group(['prefix' => 'auth'], function() {
        Route::post('signup', 'AuthController@signup')->name('auth.signup');
        Route::post('login', 'AuthController@login')->name('auth.login');
    });

    // Authenticated
    Route::group(['middleware' => ['auth:api'] ], function() {

        Route::group(['prefix' => 'auth'], function() {
            Route::post('logout', 'AuthController@logout')->name('auth.logout');
        });

        Route::group(['prefix' => '/user'], function() {
            Route::get('/profile', 'UserController@profile')->name('user.profile');
        });
    });

});