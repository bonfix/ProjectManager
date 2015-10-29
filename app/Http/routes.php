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

//Route::get('', function () {
//    return view('welcome');
//});

/*
 * Prefix routes with api
 */
Route::post('auth/github', 'Auth\AuthController@postGithub');
//Route::controllers([
//    'auth/github' => 'Auth\AuthController'
//]);
Route::get('api/me', ['middleware' => 'auth', 'uses' => 'UserController@getUser']);
//Route::put('api/me', ['middleware' => 'auth', 'uses' => 'UserController@updateUser']);

Route::group(['prefix' => 'api'], function () {
    # All version routes go here - user implicit routes
    Route::controllers([
        'auth' => 'Auth\AuthController',
        'users' => 'UserController',

    ]);


});

//Route::post('auth/github', 'Auth\AuthController@postGithub');



// Initialize Angular.js App Route.
Route::get('/', 'HomeController@index');