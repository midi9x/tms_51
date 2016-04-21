<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => 'web'], function() {
    Route::get('{lang}', ['as' => 'lang', 'uses' => 'HomeController@changeLanguage'])->where([
        'lang' => 'en', 
        'lang' => 'vi'
    ]);

    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    
    Route::post('login', ['as' => 'login', 'uses' => 'UserController@login']);

    Route::post('register', ['as' => 'register', 'uses' => 'UserController@register']);
    
    Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);
    
    Route::get('login/{social}', ['as' => 'login.{social}', 'uses' => 'UserController@redirectToProvider']);
    Route::get('login/{social}/callback', [
        'as' => 'login.{social}.callback', 
        'uses' => 'UserController@handleProviderCallback'
    ]);

    Route::group(['middleware' => ['auth', 'trainee']], function() {
        Route::resource('user', 'UserController', 
            [
                'only'  => [
                    'index', 
                    'show', 
                    'edit', 
                    'update'
                ],

                'names' => [
                    'index' => 'user.index',
                    'show' => 'user.show',
                    'edit' => 'user.edit',
                    'update' => 'user.update',
                ]
            ]
        );

    });


});
