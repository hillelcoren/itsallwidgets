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


Route::get('flutter-apps', function () {
    return redirect('/', 301);
});

Route::get('/', 'FlutterAppController@index');
Route::get('about', 'HomeController@about');
Route::get('sitemap.xml', 'FlutterAppController@sitemap');

Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

Route::get('login', function () {
    return redirect('/');
})->name('login');

Route::get('logout', 'Auth\LoginController@logout');
Route::get('log_error', 'HomeController@logError');

Route::feeds();

Route::get('flutter-app/{slug}', 'FlutterAppController@show');

Route::get('podcast', 'PodcastController@index');
Route::get('podcast/submit', 'PodcastController@create');
Route::post('podcast', 'PodcastController@store');
Route::get('podcast/{episode}/{title?}', 'PodcastController@show');
Route::get('podcast_admin/{episode_id}', 'PodcastController@edit');
Route::put('podcast_admin/{episode_id}', 'PodcastController@update');

Route::group(['middleware' => ['auth']], function () {
    Route::get('submit', 'FlutterAppController@create');
    Route::post('submit', 'FlutterAppController@store')->middleware('slug');

    Route::get('flutter-app/{flutter_app}/feature', 'FlutterAppController@feature');
    Route::get('flutter-app/{flutter_app}/approve', 'FlutterAppController@approve');
    Route::get('flutter-app/{flutter_app}/reject', 'FlutterAppController@reject');
    Route::get('flutter-app/{flutter_app}/edit', 'FlutterAppController@edit');
    Route::put('flutter-app/{flutter_app}', 'FlutterAppController@update');
});
