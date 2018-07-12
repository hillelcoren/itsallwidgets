<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('check_secret', function () {
    return view('password');
});

Route::post('check_secret', function () {
    if (request()->secret == 'studygroup') {
        session(['is_authorized' => true]);
        return redirect('/');
    } else {
        return view('password');
    }
});

Route::group(['middleware' => ['password']], function () {
    Route::redirect('/', '/flutter-apps');
    Route::get('flutter-apps', 'FlutterAppController@index');

    Route::get('google', function () {
        return view('google');
    });
    Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
    Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');
    Route::get('logout', 'Auth\LoginController@logout');

    Route::get('submit-app', 'FlutterAppController@create');
    Route::post('submit-app', 'FlutterAppController@store');

    Route::get('flutter-app/{slug}', 'FlutterAppController@show');
    Route::get('flutter-app/{id}/edit', 'FlutterAppController@edit');
    Route::put('flutter-app/{id}', 'FlutterAppController@edit');
});
